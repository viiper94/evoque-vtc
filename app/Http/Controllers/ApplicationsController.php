<?php

namespace App\Http\Controllers;

use App\Application;
use App\Http\Controllers\Controller;
use App\Member;
use App\Recruitment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApplicationsController extends Controller{

    public function index(){
        if(Auth::guest()) abort(404);
        if(Gate::allows('manage_members')){
            $apps = Application::with('member')->get();
        }else{
            $apps = Application::with('member')->where('member_id', Auth::user()->member->id)->get();
        }
        return view('evoque.applications.index', [
            'apps' => $apps->sortBy('created_at')->sortBy('status')
        ]);
    }

    public function recruitment(){
        if(Gate::denies('manage_members')) abort(403);
        $recruitment = Recruitment::all();
        return view('evoque.applications.recruitment', [
            'applications' => $recruitment->sortBy('created_at')->sortBy('status')
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
        $result = false;
        switch($application->category){
            case 1:
                $application->member->on_vacation_till = $application->vacation_till;
                $application->member->vacations += 1;
                $result = $application->member->save();
                break;
            case 3:
                foreach($application->member->stats as $stat){
                    $stat->distance = 0;
                    $stat->distance_total = 0;
                    $stat->weight = 0;
                    $stat->weight_total = 0;
                    $stat->quantity = 0;
                    $stat->quantity_total = 0;
                    $stat->level = 0;
                    $stat->bonus = 0;
                    $result = $stat->save();
                }
                break;
            case 4:
                $application->member->nickname = $application->new_nickname;
                $result = $application->member->save();
                break;
        }
        $application->status = 1;
        return $result && $application->save() ?
            redirect()->back()->with(['success' => 'Зявка принята!']) :
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
            $app->reason = htmlentities(trim($request->input('reason')));
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.vacation');
    }

    public function plate(Request $request){
        if($request->post()){
            $this->validate($request, [
                'new_plate_number' => 'required|string',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->category = 2;
            $app->new_plate_number = $request->input('new_plate_number');
            $app->reason = htmlentities(trim($request->input('reason')));
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.plate');
    }

    public function rp(Request $request){
        if($request->post()){
            $this->validate($request, [
                'reset' => 'accepted',
                'reason' => 'nullable|string'
            ]);
            $app = new Application();
            $app->member_id = Auth::user()->member->id;
            $app->old_nickname = Auth::user()->member->nickname;
            $app->category = 3;
            $app->reason = htmlentities(trim($request->input('reason')));
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
            $app->reason = htmlentities(trim($request->input('reason')));
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
            $app->reason = htmlentities(trim($request->input('reason')));
            return $app->save() ?
                redirect()->route('evoque.applications')->with(['success' => 'Зявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.applications.fire');
    }

}
