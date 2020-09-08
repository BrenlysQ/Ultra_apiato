/*userAction*/
<?php

namespace App\Containers\User\Actions;

use App\Containers\Authentication\Tasks\GetAuthenticatedUserTask;
use App\Containers\User\Tasks\DeleteUserTask;
use App\Containers\User\Tasks\FindUserByIdTask;
use App\Ship\Parents\Actions\Action;
use Illuminate\Http\Request;

/**
 * Class DeleteUserAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class DeleteUserAction extends Action
{

    /**
     * @param \App\Ship\Parents\Requests\Request $request
     *
     * @return  mixed
     */
    public function run(Request $request)
    {
        //print_r($request->all()); die;
        if ($userId = $request->id) {
            $user = $this->call(FindUserByIdTask::class, [$userId]);
        } else {
            $user = $this->call(GetAuthenticatedUserTask::class);
        }

        $this->call(DeleteUserTask::class, [$user]);

        return $user;
    }
}
