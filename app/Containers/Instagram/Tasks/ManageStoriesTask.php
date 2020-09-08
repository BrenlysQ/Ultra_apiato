<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Tasks\HistoriesTask;

class ManageStoriesTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($interactions)
    {
    	(new HistoriesTask())->run($interactions->new_stories);
        (new HistoriesTask())->run($interactions->old_stories);
   	}
}
