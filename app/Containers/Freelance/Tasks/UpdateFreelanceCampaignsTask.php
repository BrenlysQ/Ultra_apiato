<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use Illuminate\Support\Facades\Auth;

class UpdateFreelanceCampaignsTask extends Task
{
  public function run($req){
      $id_freelance = $req->input('id_freelance');
      $id = $req->input('id');
      $data = $req->input('data');
      $idate = $req->input('idate');
      $edate = $req->input('odate');
      $status = $req->input('status');
      $freelance = FreelanceModel::where('id',$id_freelance)->first();
      $campaign = FreelanceCampaignsModel::where('id',$id)->first();
      $campaign->init_plan = $idate;
      $campaign->end_plan = $edate;
      $campaign->status = $status;
      $campaign->id_freelance = $freelance->id;
      $campaign->data = $data;
      $campaign->update();
    return $campaign;
  }

}
