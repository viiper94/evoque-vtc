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
        if(Auth::guest()) abort(404);
        if(isset($id)){
            if(Gate::denies('manage_members')) abort(403);
            $app = Application::with('member')->where('id', $id)->firstOrFail();
            $rp = null;
            if($app->new_rp_profile) $rp = RpStats::where(['member_id' => $app->member_id, 'game' => $app->new_rp_profile[0]])->first();
            return view('evoque.applications.show', [
                'app' => $app,
                'rp' => $rp
            ]);
        }
        $apps = Application::with('member');
        if(Gate::denies('manage_members')){
            $apps = $apps->where('member_id', Auth::user()->member->id);
        }
        return view('evoque.applications.index', [
            'apps' => $apps->orderBy('status')->orderBy('created_at', 'desc')->paginate(15),
            'recruitments' => Recruitment::where('status', 0)->count()
        ]);
    }

    public function recruitment(Request $request, $id = null){
        if(Gate::denies('manage_members')) abort(403);
        return view('evoque.applications.recruitment', [
            'applications' => Recruitment::orderBy('status')->orderBy('created_at')->get(),
            'apps' => Application::where('status', 0)->count()
        ]);
    }

    public function acceptRecruitment(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $application = Recruitment::findOrFail($id);
        $application->status = 1;
        return $application->save() ?
            redirect()->back()->with(['success' => 'Зявка принята! Для завершения процесса добавления сотрудника на сайт, ему надо вступить в ВТК на сайте TruckersMP.']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function accept(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $application = Application::with('member', 'member.stats')->where('id', $id)->first();
        $result = true;
        switch($application->category && $request->input('accept') === '1'){
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
        }
        $application->status = $request->input('accept');
        $application->comment = $request->input('comment');
        return $result && $application->save() ?
            redirect()->back()->with(['success' => 'Зявка '. ($request->input('accept') === '1' ? 'принята' : 'отклонена') .'!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function deleteRecruitment(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $application = Recruitment::findOrFail($id);
        return $application->delete() ?
            redirect()->back()->with(['success' => 'Зявка удалена!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function delete(Request $request, $id){
        $app = Application::findOrFail($id);
        if(($app->member_id === Auth::user()->member->id && $app->status === 0) || Gate::allows('manage_members')){
            return $app->delete() ?
                redirect()->back()->with(['success' => 'Зявка удалена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }else{
            return abort(403);
        }
    }

    public function vacation(Request $request){
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

    public function plate(Request $request){
        if($request->post()){
            $this->validate($request, [
                'new_plate_number' => 'required|regex:/[0-9]{3}/',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->category = 2;
            $app->new_plate_number = $request->input('new_plate_number');
            $app->reason = $request->input('reason');
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.plate');
    }

    public function rp(Request $request){
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
