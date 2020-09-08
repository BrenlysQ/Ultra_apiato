<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use App\Containers\Freelance\Models\FreelanceModel;


class EditFreelanceCampaignsTask extends Task
{
    public function run()
    {
      $id = Input::get('id');
      $campaign = FreelanceCampaignsModel::where('id',$id)
      ->with('freelance')
      ->first();
      return json_encode($campaign);
    }

}
