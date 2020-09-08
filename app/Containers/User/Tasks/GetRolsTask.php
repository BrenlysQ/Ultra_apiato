<?php

namespace App\Containers\User\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
// use App\Containers\UltraMailer\Models\MailCampaignModel;
//use App\Containers\User\Models\User_Role;
use App\Containers\User\Models\Role;

use Illuminate\Http\Request;


class GetRolsTask extends Task
{
  public function run()
  {
    return Role::all(); 
    //return User_Role::where('user_id',$request)->get(); 
  }

}


/*namespace App\Containers\User\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
// use App\Containers\UltraMailer\Models\MailCampaignModel;
//use App\Containers\User\Models\User_Role;
use App\Containers\User\Models\Role;

use Illuminate\Http\Request;


class GetRolsTask extends Task
{
  public function run()
  {
    return Role::select('name')->get(); 
    //return User_Role::where('user_id',$request)->get(); 
  }

}*/



/*namespace App\Containers\User\Actions;

use App\Containers\User\Exceptions\UserNotFoundException;
use App\Containers\User\Tasks\FindUserByIdTask;
use App\Containers\User\Tasks\GetRolsTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Http\Request;

/**
 * Class GetUserAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
/*class GetUserRol extends Action
{

    /**
     * @param \App\Ship\Parents\Requests\Request $request
     *
     * @return  mixed
     * @throws \App\Containers\User\Exceptions\UserNotFoundException
     */
   /* public function run(Request $request)
    {
        $userId = $request->id;

        $user = $this->call(GetRolsTask::class, [$userId]);

        if (!$user) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }

}
*/