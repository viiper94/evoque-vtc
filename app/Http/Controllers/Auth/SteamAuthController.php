<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Illuminate\Support\Facades\Auth;
use TruckersMP\APIClient\Client;

class SteamAuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/evoque';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle()
    {
        if ($this->steam->validate()) {
            $steam_info = $this->steam->getUserInfo();

            if (!is_null($steam_info)) {

                $tmp = new Client();
                $tmp_info= $tmp->player($steam_info->steamID64)->get();

                if($tmp_info->getCompanyId() !== 11682){
                    return redirect(route('home'))
                        ->withErrors('error', trans('general.not_member'));
                }

                $user = $this->findOrNewUser($steam_info, $tmp_info);
                Auth::login($user, true);

                return redirect($this->redirectURL); // redirect to site
            }
        }
        return $this->redirectToSteam();
    }

    /**
     * Getting user by info or created if not exists
     *
     * @param $steam_info
     * @param $tmp_info
     * @return User
     */
    protected function findOrNewUser($steam_info, $tmp_info)
    {
        $user = User::where('steamid64', $steam_info->steamID64)->first();

        if (!is_null($user)) {
            return $user;
        }

        return User::create([
            'nickname' => $steam_info->personaname,
            'name' => $steam_info->realname,
            'image' => $steam_info->avatarfull,
            'steamid64' => $steam_info->steamID64,
            'truckersmp_id' => $tmp_info->getId()
        ]);
    }
}
