<?php

namespace App\Http\Controllers;

use App\Application;
use App\Http\Controllers\Controller;
use App\Member;
use App\Recruitment;
use App\RpStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApplicationsController extends Controller{

    public function app(Request $request, $id = null){
        if(!Auth::user()->member) abort(404);
        if(isset($id)){
            $app = Application::with('member')->where('id', $id)->firstOrFail();
            $this->authorize('claim', $app);
            $rp = null;
            if($app->new_rp_profile) $rp = RpStats::where(['member_id' => $app->member_id, 'game' => $app->new_rp_profile[0]])->first();
            return view('evoque.applications.show', [
                'app' => $app,
                'rp' => $rp
            ]);
        }
        $apps = Application::with('member');
        if(Auth::user()->cant('view', Application::class)){
            $apps = $apps->where('member_id', Auth::user()->member->id);
        }
        return view('evoque.applications.index', [
            'apps' => $apps->orderBy('created_at', 'desc')->paginate(15),
            'recruitments' => Recruitment::where('status', 0)->count()
        ]);
    }

    public function recruitment(Request $request, $id = null){
        $this->authorize('view', Recruitment::class);
        if(isset($id)){
            $app = Recruitment::where('id', $id)->firstOrFail();
            $this->authorize('claim', $app);
            return view('evoque.applications.show_recruitment', [
                'app' => $app
            ]);
        }
        return view('evoque.applications.recruitment', [
            'applications' => Recruitment::orderBy('created_at', 'desc')->paginate(15),
            'apps' => Application::where('status', 0)->count()
        ]);
    }

    public function acceptRecruitment(Request $request, $id){
        $application = Recruitment::findOrFail($id);
        $this->authorize('claim', $application);
        $application->status = $request->input('accept');
        $application->comment = $request->input('comment');
        $messages = [
            '1' => 'Зявка принята! Для завершения процесса добавления сотрудника на сайт, ему надо вступить в ВТК на сайте TruckersMP.',
            '2' => 'Зявка отклонена!',
        ];
        return $application->save() ?
            redirect()->route('evoque.applications.recruitment')->with(['success' => $messages[$application->status]]) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function deleteRecruitment(Request $request, $id){
        $this->authorize('delete', Recruitment::class);
        $application = Recruitment::findOrFail($id);
        return $application->delete() ?
            redirect()->back()->with(['success' => 'Зявка удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function accept(Request $request, $id){
        $application = Application::with('member', 'member.stats')->where('id', $id)->first();
        $this->authorize('claim', $application);
        $result = true;
        if($request->input('accept') === '1'){
            switch($application->category){
                case 1:
                    $application->member->on_vacation_till = $application->vacation_till;
                    $application->member->vacations += 1;
                    $result = $application->member->save();
                    break;
                case 3:
                    foreach($application->member->stats as $stat){
                        if($stat->game == $application->new_rp_profile[0]){
                            $stat->level = $application->new_rp_profile[1];
                            $result = $stat->save();
                        }
                    }
                    break;
                case 4:
                    $application->member->nickname = $application->new_nickname;
                    $result = $application->member->save();
                    break;
//                case 5:
//                    if($application->member){
//                        $application->member->stats->delete();
//                        $application->member->delete();
//                    }
//                    break;
            }
        }
        $application->status = $request->input('accept');
        $application->comment = $request->input('comment');
        return $result && $application->save() ?
            redirect()->route('evoque.applications')->with(['success' => 'Зявка '. ($request->input('accept') === '1' ? 'принята' : 'отклонена') .'!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $app = Application::findOrFail($id);
        $this->authorize('delete', $app);
        if(($app->member_id === Auth::user()->member->id && $app->status === 0) || Gate::allows('manage_members')){
            return $app->delete() ?
                redirect()->back()->with(['success' => 'Зявка удалена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }else{
            return abort(403);
        }
    }

    public function vacation(Request $request){
        $this->authorize('createVacation', Application::class);
        if($request->post()){
            $this->validate($request, [
                'vacation_till' => 'required|date_format:d.m.Y',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->category = 1;
            $app->vacation_till = Carbon::parse($request->input('vacation_till'))->format('Y-m-d');
            $app->reason = $request->input('reason');
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.vacation');
    }

    public function rp(Request $request){
        $this->authorize('create', Application::class);
        if($request->post()){
            $this->validate($request, [
                'new_rp_profile' => 'required|numeric',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->new_rp_profile = [$request->input('game'), $request->input('new_rp_profile')];
            $app->category = 3;
            $app->reason = $request->input('reason');
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.rp');
    }

    public function nickname(Request $request){
        $this->authorize('create', Application::class);
        if($request->post()){
            $this->validate($request, [
                'new_nickname' => 'required|string',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->new_nickname = $request->input('new_nickname');
            $app->category = 4;
            $app->reason = $request->input('reason');
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.nickname');
    }

    public function fire(Request $request){
        $this->authorize('create', Application::class);
        if($request->post()){
            $this->validate($request, [
                'fire' => 'accepted',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->category = 5;
            $app->reason = $request->input('reason');
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.fire');
    }

}
