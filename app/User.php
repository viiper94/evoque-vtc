<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'steamid64', 'truckersmp_id', 'nickname', 'image', 'city', 'country', 'vk', 'email', 'discord_id', 'fired_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'birth_date',
        'created_at',
        'fired_at',
        'updated_at'
    ];

    public function member(){
        return $this->hasOne('App\Member');
    }

    public static function deleteOldUsers(){
        $users = User::with(['member' => function($query){
            $query->withTrashed();
        }])->whereDate('fired_at', '<', Carbon::now()->subMonths(3))->get();
        foreach($users as $user){
            $hasMember = isset($user->member);
            $isFired3MothAgo = $hasMember && isset($user->member->deleted_at) && Carbon::now()->subMonths(3)->gt($user->member->deleted_at);
            $firedWithRestore = $hasMember && $user->member->restore;
            if($hasMember && $isFired3MothAgo && !$firedWithRestore){
                $user->member->forceDelete();
            }
            if(!$hasMember || ($isFired3MothAgo && !$firedWithRestore)){
                $recruitments = Recruitment::where('tmp_link', 'https://truckersmp.com/user/'.$user->truckersmp_id)->get();
                foreach($recruitments as $recruitment){
                    $recruitment->delete();
                }
                $changes_by_user = Audit::where('user_id', $user->id)->get();
                foreach($changes_by_user as $change){
                    $change->delete();
                }
                $user->delete();
            }
        }
    }

    public static function andCan(Array|String $abilities, $argument) :bool{
        is_array($abilities) ? : $abilities = [$abilities];
        if(Auth::guest()) return false;
        foreach($abilities as $ability){
            if(Auth::user()?->cant($ability, $argument)){
                return false;
            }
        }
        return true;
    }

    public static function orCan(Array|String $abilities, $argument) :bool{
        is_array($abilities) ? : $abilities = [$abilities];
        if(Auth::guest()) return false;
        foreach($abilities as $ability){
            if(Auth::user()?->can($ability, $argument)){
                return true;
            }
        }
        return false;
    }

}
