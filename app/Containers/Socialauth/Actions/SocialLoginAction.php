<?php

namespace App\Containers\SocialAuth\Actions;

use App\Containers\Authentication\Tasks\ApiLoginThisUserObjectTask;
use App\Containers\SocialAuth\Tasks\CreateUserBySocialProfileTask;
use App\Containers\SocialAuth\Tasks\FindSocialUserTask;
use App\Containers\SocialAuth\Tasks\GetUserSocialProfileTask;
use App\Containers\SocialAuth\Tasks\UpdateUserSocialProfileTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Auth;

/**
 * Class SocialLoginAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SocialLoginAction extends Action
{

    /**
     * ----- if has social profile
     * --------- [A] update his social profile info
     * ----- if has no social profile
     * --------- [C] create new record
     *
     * @param $request
     * @param $provider
     *
     * @return  mixed
     */
    public function run($userfromsocial, $provider)
    {
        // TODO: needs refactoring so bad :D

        // fetch the user data from facebook
        // checking if some data are available in the response
        // (these lines are written to make this function compatible with multiple providers)
        $tokenSecret = isset($userfromsocial->tokenSecret) ? $userfromsocial->tokenSecret : null;
        $expiresIn = isset($userfromsocial->expiresIn) ? $userfromsocial->expiresIn : null;
        $refreshToken = isset($userfromsocial->refreshToken) ? $userfromsocial->refreshToken : null;
        $avatar_original = isset($userfromsocial->avatar_original) ? $userfromsocial->avatar_original : null;

        // check if the social ID exist on any of our users, and get that user in case it was found
        $socialUser = $this->call(FindSocialUserTask::class, [$userfromsocial->email]);
        if ($socialUser) {
            // THIS IS: A USER AND ALREADY HAVE A SOCIAL PROFILE
            // DO: UPDATE THE EXISTING USER SOCIAL PROFILE.

            // Only update tokens and updated information. Never override the user profile.
            $user = $this->call(UpdateUserSocialProfileTask::class, [
                $socialUser->id,
                $userfromsocial->token,
                $expiresIn,
                $refreshToken,
                $tokenSecret,
                $userfromsocial->avatar,
                $avatar_original
            ]);
        } else {
            // THIS IS: A NEW USER
            // DO: CREATE NEW USER FROM THE SOCIAL PROFILE INFORMATION.
            $user = $this->call(CreateUserBySocialProfileTask::class, [
                $provider,
                $userfromsocial->token,
                $userfromsocial->id,
                $userfromsocial->nickname,
                $userfromsocial->name,
                $userfromsocial->email,
                $userfromsocial->avatar,
                $tokenSecret,
                $expiresIn,
                $refreshToken,
                $avatar_original
            ]);
        }

        //$user = $this->call(ApiLoginThisUserObjectTask::class, [$user]);
        //$user = '';
        //echo $user->id; die;
        $user = Auth::loginUsingId($user->id);
        if ( ! $user)
        {
            throw new Exception('Error logging in');
        }
        //Auth::login($user); die;
        return $user;
    }

}
