<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class StoreResponseCommentTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($comment,$thread_id)
    {
        $data = array(
                'userid_interaction' => Input::get('user_id'),
                'from_user' => getenv('INSTA_USERID'),
                'item_type' => 'Comment answered',
                'timestamp_message'=> Carbon::now()->toDateTimeString(),
                'threads_id' => $thread_id,
                'text_message' => Input::get('message'),
                'comment_id' => $comment->id,
                'status_id' => 2
            );
        $direct = new InstaMessagesModel($data);
        $direct->save();
        return $direct; 
    }
}
