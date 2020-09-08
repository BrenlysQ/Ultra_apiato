<?php

namespace App\Containers\User\Actions;

use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Hash;
use App\Containers\User\Tasks\UpdateUserTask;
use App\Containers\User\Tasks\DeleteUserTask;
use App\Containers\User\Tasks\GetUserTask ;



class UserAction extends Action
{

   public function UpdateUser($request)
   {
       return $this->call(UpdateUserTask::class, [$request]);
   }

   public function DeleteUser(Request $request)
   {
         $user =  (new DetleteUserTask())->run();
   }

   public function GetUser(Request $request)
   {
            $users =  (new GetUserTask())->run();
            return $users;
   }


}
