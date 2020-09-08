<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;



class GetFreelanceBankInfoTask extends Task
{
    public function run($req)
    {
      $id = $req->input('id');
      $freelance = FreelanceModel::where('id',$id)
                                  ->with('bankinfo')
                                  ->first();
	    return $freelance;
    }

}
