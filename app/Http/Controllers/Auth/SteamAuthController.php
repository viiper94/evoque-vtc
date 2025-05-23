<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Member;
use App\Recruitment;
use App\RpStats;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Illuminate\Support\Facades\Auth;
use TruckersMP\APIClient\Client;
use TruckersMP\APIClient\Exceptions\ApiErrorException;
use TruckersMP\APIClient\Exceptions\RequestException;

class SteamAuthController extends Controller{

    protected $steam;
    protected $redirectURL = '/';

    public function __construct(SteamAuth $steam){
        $this->steam = $steam;
    }

    public function redirectToSteam(){
        return $this->steam->redirect();
    }

    public function handle(){
        if ($this->steam->validate()) {
            $steam_info = $this->steam->getUserInfo();

            if (!is_null($steam_info)) {

                try{
                    $tmp = new Client();
                    $tmp_info= $tmp->player($steam_info->steamID64)->get();
                }catch(ClientException|ServerException $e){
                    return redirect()->route('home')->withErrors(['Игрок с таким ID не найден.']);
                }

                if(!$tmp_info || ($tmp_info->getCompanyId() !== 11682 && $tmp_info->getId() !== 131815)){
                    return redirect(route('apply'))
                        ->withErrors([trans('general.not_member')]);
                }

                if($tmp_info->getId() === 131815){
                    $user = User::find(1);
                }else{
                    $user = $this->findOrNewUser($steam_info, $tmp_info);
                }

                if($user->member && $user->member->trashed() && $user->member->restore && $tmp_info->getId() !== 131815)
                    return redirect(route('apply'))
                    ->withErrors([trans('general.not_visible_member')]);

                Auth::login($user, true);

                return redirect($this->redirectURL); // redirect to site
            }
        }
        return $this->redirectToSteam();
    }

    protected function findOrNewUser($steam_info, $tmp_info){
        $user = User::with(['member' => function($query){
            $query->withTrashed();
        }])->where('steamid64', $steam_info->steamID64)->first();

        if(!is_null($user)){
            if(!is_null($user->member)){
                if($user->member->trashed()){
                    if(!$user->member->restore && $tmp_info->getId() !== 131815){
                        $user->update([
                            'fired_at' => null
                        ]);
                        $this->resetMember($user->member, $tmp_info);
                    }
                }
            }else{
                $user->update([
                    'fired_at' => null
                ]);
                $this->createMember($user, $tmp_info);
            }
        }else{
            $user = $this->createUser($steam_info, $tmp_info);
            $this->createMember($user, $tmp_info);
        }
        return $user;
    }

    private function createUser($steam_info, $tmp_info){
        $recruitment = Recruitment::where('tmp_link', 'https://truckersmp.com/user/'.$tmp_info->getId())->latest()->first();
        return User::create([
            'name' => $recruitment ? $recruitment->name : $steam_info->realname,
            'image' => $steam_info->avatarfull,
            'steamid64' => $steam_info->steamID64,
            'truckersmp_id' => $tmp_info->getId(),
            'vk' => $recruitment ? $recruitment->vk_link : null,
        ]);
    }

    private function createMember($user, $tmp_info){
        $member = Member::create([
            'user_id' => $user->id,
            'nickname' => str_replace('[EVOQUE] ', '', $tmp_info->getName()),
            'join_date' => Carbon::today(),
            'trainee_until' => Carbon::today()->addDays(10),
        ]);
        $member->role()->attach('14');
        $member->save();
        $member->stats()->saveMany([
            new RpStats(['game' => 'ets2']),
            new RpStats(['game' => 'ats']),
        ]);
    }

    private function resetMember($member, $tmp_info){
        $member->fill([
            'join_date' => Carbon::today(),
            'trainee_until' => Carbon::today()->addDays(10),
            'nickname' => str_replace('[EVOQUE] ', '', $tmp_info->getName()),
            'scores' => 0,
            'money' => 0,
            'convoys' => 0,
            'trainee_convoys' => 0,
            'vacations' => 0,
            'plate' => null,
            'sort' => false,
        ]);
        $member->money = 0;
        $member->visible = true;
        $member->restore = false;
        $member->role()->detach();
        $member->role()->attach('14');
        $member->save();
        $member->stats()->delete();
        $member->stats()->saveMany([
            new RpStats(['game' => 'ets2']),
            new RpStats(['game' => 'ats']),
        ]);
        $member->restore();
    }

}
