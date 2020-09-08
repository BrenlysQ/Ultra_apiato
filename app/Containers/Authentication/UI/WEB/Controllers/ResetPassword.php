<?php

namespace App\Containers\Authentication\UI\WEB\Controllers;
use App\Commons;
use App\Ship\Parents\Controllers\WebController;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Containers\User\Models\User;
use Illuminate\Http\Request;
use App\Containers\User\Models\PwdReset;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\PasswordResetEmail;
class ResetPassword extends WebController
{

    public function resetForm(Request $req)
    {
      if($user = User::where('email', $req->email_reset)->first())
      {
        return json_encode(array("token" => ($this->SendToken($user))->token));
      }else
      {
        return json_encode(array('error' => true));
      }
    }
    public function resetFormAct(Request $request){
      $token = $request->token;
      $code = $request->reset_code;
      $reset = PwdReset::whereRaw("token = '" . $token . "'
                                    AND code = '" . $code . "'
                                    AND TIMESTAMPDIFF(MINUTE,created_at,'" . date('Y-m-d H:i:s') . "') < 30")->first();
      if($reset){
        $user = User::where('email', $reset->email)->first();
        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();
        PwdReset::where('email',$reset->email)->delete();
        return $user->tojson();
      }
      return json_encode(array('error' => true));
    }
    public function SendToken($user){
      PwdReset::where('email',$user->email)->delete();
      $reset = new PwdReset();
      $reset->email = $user->email;
      $reset->token = str_random(64);
      $reset->code = str_random(6);
      $reset->save();
      $data = CommonActions::CreateObject();
      $data->reset = $reset;
      $data->user = $user;
      Mail::to($user->email)->send(new PasswordResetEmail($data));
      return $reset;
    }
}
