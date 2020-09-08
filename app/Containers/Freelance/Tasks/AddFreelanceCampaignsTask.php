<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use Illuminate\Support\Facades\Auth;

class AddFreelanceCampaignsTask extends Task
{
  public function run($req){
      $id = $req->input('id_freelance');
      $data = $req->input('data');
      $idate = $req->input('idate');
      $edate = $req->input('odate');
      $user = Auth::user();
      $freelance = FreelanceModel::where('id',$id)->first();
      $campaign = new FreelanceCampaignsModel();
      $campaign->init_plan = $idate;
      $campaign->end_plan = $edate;
      $campaign->status = 1;
      $campaign->created_by = $user->id;
      $campaign->id_freelance = $freelance->id;
      $campaign->data = $data;
      $campaign->save();
    return $campaign;
  }

}
