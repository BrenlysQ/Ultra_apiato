<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;


class DeleteFreelanceCampaignsTask extends Task
{
    public function run()
    {
      $id = Input::get('id');
	    $freelance = FreelanceCampaignsModel::findOrFail($id);
      $freelance->delete();
	    return $freelance;
    }

}
