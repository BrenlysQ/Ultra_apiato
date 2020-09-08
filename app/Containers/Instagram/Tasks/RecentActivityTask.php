<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use InstagramAPI\Response;

class RecentActivityTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($ig)
    {
    	return $ig->request('news/inbox/')->getResponse(new Response\ActivityNewsResponse());
   	}
}
