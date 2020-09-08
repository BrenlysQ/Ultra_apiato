<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Intervention\Image\ImageManagerStatic as Image;


class EditFreelanceTask extends Task
{
    public function run()
    {
      $usite = Input::get('usite');
      if ($usite == 1){
        $email = Input::get('email');
        $freelance = FreelanceModel::where('email',$email)
        ->with('features', 'bankinfo', 'confirmcode')
        ->first();
      }else {
        $id = Input::get('id');
        $freelance = FreelanceModel::where('id',$id)
        ->with('features', 'bankinfo', 'confirmcode')
        ->first();
      }
	    return $freelance;
    }

}
