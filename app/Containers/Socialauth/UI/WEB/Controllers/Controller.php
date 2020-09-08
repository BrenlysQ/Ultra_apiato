<?php

namespace App\Containers\SocialAuth\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Containers\SocialAuth\Actions\SocialLoginAction;

/**
 * Class Controller
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Controller extends WebController
{

    /**
     * @param $provider
     *
     * @return  mixed
     */
    public function redirectAll($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param $provider
     *
     * @return  mixed
     */
    public function handleCallbackAll(Request $req, $provider)
    {
        $user = Socialite::driver($provider)->user();

        $user = (new SocialLoginAction())->run($user,$provider);
        $data = $req->session()->all();
        //dd($user);
        return redirect(session('urlintended'));
    }

}
