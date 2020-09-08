<?php

namespace App\Containers\Instagram\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;

class ManageMessagesTask extends Task
{
    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */

    public function run($messages)
    {
        /*This is the user of the plusultraviajes account 1769706132*/

		foreach ($messages as $message) {
           foreach ($message->items as $item) {
               $exist_comment = InstaMessagesModel::where('item_id',$item->item_id)->first();
               $type = $item->item_type;
               if (!$exist_comment){
                   $data = new InstaMessagesModel();
                   $data->userid_interaction = $message->users[0]->pk;
                   $data->item_id = $item->item_id;
                   $data->from_user = $item->user_id;
                   $data->item_type = $item->item_type;
                   $data->timestamp_message = $message->last_permanent_item->timestamp;
                   $data->like = $item->like;
                   $data->threads_id = $message->thread_id;
                   $data->status_id = 1;
                   if ($type == 'link'){
                       $data->text_message = $item->link->text;
                   }else{
                       $data->text_message = $item->text;
                   }
                   $data->save();
               }
           }
       }
   	}
}
