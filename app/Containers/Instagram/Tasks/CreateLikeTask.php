<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Instagram\Models\InstaMediaModel;
use App\Containers\Instagram\Tasks\CreateMediaTask;
use App\Containers\Instagram\Models\InstaLikesModel;

class CreateLikeTask extends Task
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
    	$exist_like = InstaLikesModel::where([
                        ['user_id',$interaction->args->links[0]->id],
                        ['timestamp_like',$interaction->args->timestamp]
                    ])->first();
        if(!$exist_like){
            $username = explode(" ",$interaction->args->text);
            $exist_media = InstaMediaModel::where('media_id',$interaction->args->media[0]->id)->first();
            if($exist_media){
                (new CreateMediaTask())->run($exist_media,$interaction,$username,$comment = false);
            }else{
                (new CreateMediaTask())->run($exist_media = false,$interaction,$username,$comment = false);
            }
        }
   	}
}
