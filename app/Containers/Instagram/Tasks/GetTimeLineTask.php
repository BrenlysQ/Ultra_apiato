<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;

class GetTimeLineTask extends Task
{

    public function run($thread)
    {
		$data = InstaMessagesModel::where('threads_id','=',$thread)
		->orderBy('timestamp_message', 'asc')
		->get();
		$data->load('insta_user');
		return $data;
   	}
}
