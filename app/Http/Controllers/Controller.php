<?php

namespace App\Http\Controllers;

use App\Convoy;
use App\Member;
use App\Recruitment;
use App\Role;
use App\Steam;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use TruckersMP\APIClient\Client;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        return view('index', [
            'members_count' => Member::where('visible', 1)->count()
        ]);
    }

    public function privacy(){
        return view('privacy');
    }

    public function terms(){
        return view('terms');
    }

    public function apply(Request $request){
        if($request->post()){
            $this->validate($request, [
                'name' => 'required|string',
                'age' => 'required|numeric',
                'vk_link' => 'required|url',
                'discord_name' => 'nullable|string',
                'tmp_link' => 'required|url',
                'rules_agreed' => 'required',
                'requirements_agreed' => 'required',
                'terms_agreed' => 'required',
            ]);

            $tmp = new Client();
            $steam = new Steam();
            $tmp_data = $tmp->player(explode('user/', $request->input('tmp_link'))[1])->get();
            $steam_data = $steam->getPlayerData($tmp_data->getSteamID64());
            $steam_games = $steam->getSCSGamesData($tmp_data->getSteamID64());
            if(!$steam_games['ets2'] && !$steam_games['ats']){
                return redirect()->back()->withErrors(['Ваш профиль Steam скрыт! Откройте его и подайте заявку еще раз.']);
            }

            $application = new Recruitment();
            $application->fill($request->post());
            $application->steam_link = $steam_data['profileurl'];
            $application->nickname = $tmp_data->getName();
            $application->have_ats = (bool)$steam_games['ats'];
            $application->hours_played = $steam_games['ets2'];
            $application->tmp_join_date = $tmp_data->getJoinDate()->format('Y-m-d');
            if($request->input('referral')) $application->referral = htmlentities(trim($request->input('referral')));
            $application->status = 0;
            return $application->save() ?
                redirect()->route('apply')->with(['success' => 'Заявка успешно подана! Наш Отдел кадров скоро свяжется с вами.']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('apply');
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
