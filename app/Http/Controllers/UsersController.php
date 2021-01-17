<?php

namespace App\Http\Controllers;

use App\Application;
use App\Gallery;
use App\Kb;
use App\Member;
use App\Recruitment;
use App\RpReport;
use App\RpStats;
use App\Tab;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Models\Audit;
use TruckersMP\APIClient\Client;

class UsersController extends Controller{

    public function index(){
        $this->authorize('view', User::class);
        return view('evoque.users', [
            'users' => User::with('member')->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function setAsMember(Request $request, $id){
        $user = User::findOrFail($id);
        $this->authorize('setAsMember', $user);
        $member = new Member();
        $member->user_id = $user->id;
        $member->join_date = Carbon::now();
        $tmp = new Client();
        $tmp_info= $tmp->player($user->steamid64)->get();
        $member->nickname = str_replace('[EVOQUE] ', '', $tmp_info->getName());
        if($member->save()){
            $member->role()->attach('14');
            $member->update();
            return redirect()->route('evoque.admin.users')->with(['success' => 'Сотрудник успешно добавлен!']);
        }
        return redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function dumpData(Request $request){
        if(!$request->ajax()) abort(404);
        $user = User::findOrFail(Auth::id());

        // Querying data
        $recruitments = Recruitment::where('tmp_link', 'https://truckersmp.com/user/'.$user->truckersmp_id)->get();
        $gallery = Gallery::where('uploaded_by', $user->id)->get();
        $kb = Kb::where('author', $user->id)->get();

        $member = Member::where('user_id', $user->id)->first() ?? null;
        $applications = $member ? Application::where('member_id', $member->id)->get() : null;
        $rp_reports = $member ? RpReport::where('member_id', $member->id)->orWhere('comment_by', $member->nickname)->get() : null;
        $rp_stats = $member ? RpStats::where('member_id', $member->id)->get() : null;
        $tabs = $member ? Tab::with('lead')->where('member_id', $member->id)->orWhere('lead_id', $member->id)->get() : null;

        $changes_by_user = Audit::where('user_id', $user->id)->get();
        $changes_of_member = $member ? Audit::where(['auditable_type' => 'App\Member', 'auditable_id' => $member->id])->get() : null;

        $data = [
            'user' => $user,
            'recruitments_applications' => $recruitments->makeHidden(['id'])->each(function($recruitment){
                $recruitment->status = $recruitment->getStatus();
            }),
            'gallery' => $gallery->makeHidden(['id', 'on_main'])->each(function($photo) use ($user){
                $photo->uploaded_by = $user->name;
            }),
            'kb' => $kb->makeHidden(['id', 'sort'])->each(function($article) use ($user){
                $article->author = $user->name;
            }),
            'member' => $member->makeHidden(['id', 'sort', 'user_id']),
            'member_history' => $changes_of_member
                ->makeHidden(['id', 'user_type', 'user_id', 'ip_address', 'user_agent', 'tags', 'auditable_type'])
                ->each(function($audit) use ($member){
                    $audit->auditable_id = $member->nickname;
                }),
            'member_applications' => $applications->makeHidden(['id'])->each(function($application) use ($member){
                $application->member_id = $member->nickname;
                $application->category = 'Заявка на '.$application->getCategory();
                $application->status = $application->getStatus();
            }),
            'rp_reports' => $rp_reports->makeHidden(['id'])->each(function($report) use ($member){
                $report->member_id = $member->nickname;
                $report->status = $report->getStatus();
            }),
            'rp_stats' => $rp_stats->makeHidden(['id'])->each(function($stats) use ($member){
                $stats->member_id = $member->nickname;
            }),
            'tab_screens' => $tabs->makeHidden(['id', 'lead'])->each(function($tab) use ($member){
                $tab->member_id = $member->nickname;
                $tab->lead_id = $tab->lead->nickname;
                $tab->status = $tab->getStatus();
            }),
            'changes' => $changes_by_user->makeHidden(['id', 'user_type'])->each(function($audit) use ($user){
                $audit->user_id = $user->name;
            })
        ];

        $file = $user->id.'.'.time().'.json';
        file_put_contents(public_path('dumps/'.$file), json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        return response()->json($file);
    }

}
