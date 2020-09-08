<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaCommentsModel;
use App\Containers\Instagram\Models\InstaMessagesModel;
use Illuminate\Support\Facades\Input;

class UpdateStatusTask extends Task
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
        if (Input::get('from_message')) {
            $message = InstaMessagesModel::where('item_id',Input::get('item'))->first();
            $message->status_id = 2;
            $message->update();
            return $message;
        }else{
            $comment = InstaCommentsModel::where('comment_id',Input::get('comment'))->first();
            $comment->status_id = 2;
            $comment->update();
            return $comment;
        }
   	}
}
