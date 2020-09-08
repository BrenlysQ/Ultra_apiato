<?php

namespace App\Containers\User\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use App\Containers\User\Actions\RegisterUserAction;
use App\Containers\User\UI\API\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Containers\User\UI\API\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
/**
 * Class Controller
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class Controller extends WebController
{

    /**
     * @return  \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sayWelcome()
    {   // user say welcome
        return view('user::user-welcome');
    }
    public function registerUser(RegisterUserRequest $request)
    {
        $user = $this->call(RegisterUserAction::class, [$request]);
        Auth::loginUsingId($user->id);
        return session('urlintended');
    }
}
