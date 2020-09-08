<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;

class ListMessagesTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run()
    {
    	$messages = InstaMessagesModel::where([
                    ['from_user','<>',getenv('INSTA_USERID')],
                    ['status_id',1]
                ])
				->where(function($q) {
					  $q->where('item_type', 'link')
						->orWhere('item_type', 'text');
				})
				->orderBy('timestamp_message', 'desc')
				->get();
		$messages->load('insta_user');
		return $messages;
   	}
}
