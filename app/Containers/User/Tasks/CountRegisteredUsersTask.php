<?php

namespace App\Containers\User\Tasks;

use App\Containers\User\Data\Repositories\UserRepository;
use App\Ship\Parents\Actions\Action;
use App\Ship\Criterias\Eloquent\NotNullCriteria;
use Illuminate\Support\Facades\App;

/**
 * Class CountRegisteredUsersTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CountRegisteredUsersTask extends Action
{
    /**
     * @return  mixed
     */
    public function run()
    {
        return App::make(UserRepository::class)->pushCriteria(new NotNullCriteria('email'))->all()->count();
    }

}
