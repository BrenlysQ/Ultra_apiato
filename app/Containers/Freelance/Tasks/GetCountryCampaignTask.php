<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use Carbon\Carbon;
use DB;




class GetCountryCampaignTask extends Task
{
    public function run($freelance, $campaigns)
    {

      foreach ($freelance as $key => $free) {
        foreach ($campaigns as $campaign) {
          if ($free->id == $campaign->id_freelance) {
            $endplan = Carbon::parse($campaign->end_plan);
            $data = json_decode($campaign->data);
            if ($data->country == $free->country && $endplan->diffInDays(Carbon::now()) >= 1) {
              $freelance[$key] = $freelance[0];
              $freelance[0] = $free;
              $freelance[0]->sponsored = 'sponsored';
              return $freelance;
            }
          }
        }
      }
      return $freelance;
    }

}
