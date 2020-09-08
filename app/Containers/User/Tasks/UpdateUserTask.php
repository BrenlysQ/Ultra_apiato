<?php
namespace App\Containers\User\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\User\Models\User;
use App\Containers\User\Models\User_Role;
use Illuminate\Support\Facades\Input;
use App\Containers\User\Data\Repositories\UserRepository;
use Illuminate\Support\Facades\App;

class UpdateUserTask extends Task{

    public function run($request)
    {

            $userData = array (  
              "name" => Input::get('name'),
              "email" => Input::get('email'),
              "birth" => Input::get(''),
              "gender" => Input::get(''),
            );
     

            /*$user = User::find($request->id);
            $user->update($userData);


            User_Role::where('id',$user)->delete();
            User::where('id',$user)->delete();*/
            return App::make(UserRepository::class)->update($userData, $request->id);
    }
}