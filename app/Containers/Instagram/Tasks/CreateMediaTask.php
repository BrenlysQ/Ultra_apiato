<?php

namespace App\Containers\Instagram\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Instagram\Models\InstaMediaModel;

class CreateMediaTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */

    public function run($exist_media,$interaction,$username,$comment)
    {   
    	if ($exist_media){
            CreateMediaTask::storyType($interaction->story_type,$interaction,$username,$exist_media,$comment);
    	}else{
            $media = CreateMediaTask::saveMedia($interaction);
            CreateMediaTask::storyType($interaction->story_type,$interaction,$username,$media,$comment);
    	}
   	}
    public static function storyType($type,$interaction,$username,$media,$comment = false){
        switch ($type) {
            case 12:
                CreateMediaTask::saveComment($media,$interaction,$username,$comment); 
                break;
            case 60:
                CreateMediaTask::saveLike($media,$interaction,$username);
                break;
        }
    }
    public static function saveMedia($interaction){
        $media = new InstaMediaModel();
        $media->media_id = $interaction->args->media[0]->id;
        $media->url_img = $interaction->args->media[0]->image;
        $media->save();
        return $media;
    }
    public static function saveComment($media,$interaction,$username,$comment){
        $media->comments()->create([
            'comment_id' => $comment,
            'comment' => $interaction->args->text,
            'user_id' => $interaction->args->links[0]->id,
            'user' => $username[0],
            'timestamp_comment' => $interaction->args->timestamp,
            'status_id' => 1
        ]);
    }  
    public static function saveLike($media,$interaction,$username){
        $media->likes()->create([
            'user_id' => $interaction->args->links[0]->id,
            'user' => $username[0],
            'timestamp_like' => $interaction->args->timestamp,
        ]);
    }
}
