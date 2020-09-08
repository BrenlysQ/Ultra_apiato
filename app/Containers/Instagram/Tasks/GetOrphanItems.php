<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;
class GetOrphanItems extends Task
{

    public function run()
    {
		$messages = InstaMessagesModel::doesntHave('insta_user')->get();
		return $messages;
   	}
}
