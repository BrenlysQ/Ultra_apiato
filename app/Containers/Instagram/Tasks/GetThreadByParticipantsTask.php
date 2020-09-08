<?php
namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;
use InstagramAPI\Response;

class GetThreadByParticipantsTask extends Task
{
    public function run($ig,$thread){

		$th = InstaMessagesModel::where('threads_id',$thread)->first();

	    $request = $ig->request('direct_v2/threads/get_by_participants/')
			->addParam('recipient_users', '['.$th->userid_interaction.']');
		$messages = $request->getResponse(new Response\DirectThreadResponse());
		//print_r($messages->thread->items); die;
		foreach($messages->thread->items as $message){
			if(($message->item_type == 'text' || $message->item_type == 'link') && !$exists = InstaMessagesModel::where('item_id',$message->item_id)->first()){
				$msg = new InstaMessagesModel();
				$msg->item_id = $message->item_id;
				$msg->userid_interaction = $th->userid_interaction;
				$msg->from_user = $message->user_id;
				$msg->item_type = $message->item_type;
				$msg->timestamp_message = $message->timestamp;
				$msg->threads_id = $thread;
				if ($message->item_type == 'link'){
					//echo $message->link->text; die;
					$msg->text_message = $message->link->text;
				}else{
					$msg->text_message = $message->text;
				}
				//$msg->text_message = $message->text;
				$msg->status_id = 1;
				$msg->save();
				//echo 'NEW';
			}
		}
   	}

}
