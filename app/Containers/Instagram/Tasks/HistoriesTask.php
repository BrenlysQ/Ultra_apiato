<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Tasks\CreateCommentTask;
use App\Containers\Instagram\Tasks\CreateLikeTask;

class HistoriesTask extends Task
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
    	foreach ($interactions as $key => $interaction) {
    		switch ($interaction->story_type) {
    			case 12:
    				(new CreateCommentTask())->run($interaction);
    				break;
    			case 60:
    				(new CreateLikeTask())->run($interaction);
    				break;
    		}
		}
   	}
}
