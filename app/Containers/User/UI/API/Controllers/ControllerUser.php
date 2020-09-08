<?php

namespace App\Containers\User\UI\API\Controllers;

use App\Containers\User\Actions\DeleteUserAction;
use App\Containers\User\Actions\GetUserAction;
use App\Containers\User\Actions\RegisterUserAction;
use App\Containers\User\Actions\UpdateUserAction;
use App\Containers\User\Actions\UserAction;
use App\Containers\User\Actions\GetUserRol;
use App\Containers\User\UI\API\Requests\CreateAdminRequest;
use App\Containers\User\UI\API\Requests\DeleteUserRequest;
use App\Containers\User\UI\API\Requests\GetAuthenticatedUserRequest;
use App\Containers\User\UI\API\Requests\GetUserByIdRequest;
use App\Containers\User\UI\API\Requests\ListAllUsersRequest;
use App\Containers\User\UI\API\Requests\RegisterUserRequest;
use App\Containers\User\UI\API\Requests\UpdateUserRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;


class ControllerUser extends ApiController
{
    public $update;
    public function __construct(){
        $this->update = new UserAction();
    }
    public function registerUser(RegisterURequest $request)
    {
        return $this->call(RegisterUserAction::class, [$request]);
    }

    public function updateUser(Request $request)
    {
        return $this->update->UpdateUser($request);
    }


    public function deleteUser(DeleteUserRequest $request)
    {
        return $this->call(DeleteUserAction::class, [$request]);
    }


    public function getRols(Request $request)
    {
        return $this->call(GetUserRol::class, [$request]);
    }
}
