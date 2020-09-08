<?php

namespace App\Containers\UltraMailer\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraMailer\Models\ConfigHeaderModel;

class FullConfigHeader extends Task
{
    public function run() {
        $title = Input::get('title');
        $from = Input::get('from');
        $confijson = Input::get('config_json');
        $replyto = Input::get('reply_to');
        $name = Input::get('name');
    
       // $HeaderMailCampaign = ConfigHeaderModel::where('id',$id)->first();
		$Header = new ConfigHeaderModel();
		$Header->title = $title;
        $Header->from = $from;
        $Header->config_json = $confijson;
        $Header->reply_to = $replyto;
        $Header->name = $name;
        $Header->save();
        dd(json_decode($Header));
	  return $Header;
    }
}