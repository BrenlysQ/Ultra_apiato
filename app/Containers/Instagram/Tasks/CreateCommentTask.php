<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaCommentsModel;
use App\Containers\Instagram\Models\InstaMediaModel;
use App\Containers\Instagram\Tasks\CreateMediaTask;

class CreateCommentTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($interaction)
    {
        $comment = $interaction->args->comment_id;
        $exist_comment = InstaCommentsModel::where('comment_id',$comment)->first();
        if(!$exist_comment){
            $username = explode(" ",$interaction->args->text);
            $exist_media = InstaMediaModel::where('media_id',$interaction->args->media[0]->id)->first();
            if($exist_media){
                (new CreateMediaTask())->run($exist_media,$interaction,$username,$comment);
            }else{
                (new CreateMediaTask())->run($exist_media,$interaction,$username,$comment);
            }
        }
   	}
}
