<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use App\Recruitment;
use App\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        return view('index', [
            'members_count' => Member::where('visible', 1)->count()
        ]);
    }

    public function rulesNobodyRead(){
        return view('rules');
    }

    public function apply(Request $request){
        if($request->post()){
            $this->validate($request, [
                'name' => 'required|string',
                'nickname' => 'required|string',
                'age' => 'required|numeric',
                'hours_played' => 'required|numeric',
                'vk_link' => 'required|url',
                'steam_link' => 'required|url',
                'tmp_link' => 'required|url',
                'rules_agreed' => 'required',
                'requirements_agreed' => 'required',
            ]);
            $application = new Recruitment();
            $application->fill($request->post());
            $application->have_mic = $request->input('have_mic') == 'on';
            $application->have_ts3 = $request->input('have_ts3') == 'on';
            $application->have_ats = $request->input('have_ats') == 'on';
            $application->referral = htmlentities(trim($request->input('referral')));
            $application->status = 0;
            return $application->save() ?
                redirect()->route('home')->with(['success' => 'Заявка успешно подана!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('apply');
    }

    public function convoys(){
        return view('convoys', [
            'convoy' => Convoy::where(['visible' => '1', 'public' => '1'])->first()
        ]);
    }

    public function members(){
        return view('members', [
            'roles' => Role::with(['members' => function($query){
                $query->where('visible', 1);
            }, 'members.user', 'members.role' => function($query){
                $query->where('visible', 1);
            }])->where('visible', 1)->get()->groupBy('group')
        ]);
    }

}
