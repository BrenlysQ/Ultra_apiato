<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMessagesModel;
use Mail;
use App\Mail\InstagramRequest;
use Illuminate\Support\Facades\Input;
use App\Containers\Instagram\Models\InstaCommentsModel;
use App\Commons\CommonActions;
use App\Containers\Instagram\Tasks\GetTimeLineTask;

class RedirectClientTask extends Task
{
    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($timeline)
    {
		//print_r($time_line); die;
    	$agents_email = json_decode(Input::get('agent_email'));
		//print_r($agents_email); die;
		//print_r($agents_email); die;
        $item_id = Input::get('item');
		$thread_id = Input::get('thread');
		//$timeline = $this->call(GetTimeLineTask::class,[$thread_id]);
        $direct = InstaMessagesModel::where([
					['item_id',$item_id],
				])->first();
		$relation = InstaMessagesModel::select('comment_id')
					->where([
						['threads_id',$thread_id],
						['comment_id','<>',null]
					])
					->orderBy('threads_id','desc')
					->first();
		if($relation == null){
			$data = CommonActions::CreateObject();
			$data->info = $direct->text_message;
			$data->comment = null;
		}else{
			$comment = InstaCommentsModel::find($relation->comment_id);
			$data = CommonActions::CreateObject();
			$data->info = $direct->text_message;
			$data->comment = $comment->comment;
		}
		$direct->status_id = 2;
        $direct->update();
		foreach($agents_email as $email){
			//print_r($email->value); die;
			Mail::to($email->value)->send(new InstagramRequest($data, $timeline));
		}
		//Mail::to('enrique.tecnologia@viajesplusultra.com')->send(new InstagramRequest($data));
        return 'Cliente redireccionado';
   	}
}
