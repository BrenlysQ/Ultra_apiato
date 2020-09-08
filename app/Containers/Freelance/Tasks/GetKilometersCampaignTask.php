<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use Carbon\Carbon;
use DB;




class GetKilometersCampaignTask extends Task
{
    public function run($freelance, $campaigns, $ulat, $ulng)
    {

      foreach ($freelance as $key => $free) {
        foreach ($campaigns as $campaign) {
          if ($free->id == $campaign->id_freelance) {
            $data = json_decode($campaign->data);
            $endplan = Carbon::parse($campaign->end_plan);
            $flat = deg2rad($campaign->freelance->latitude);
            $flng = deg2rad($campaign->freelance->longitude);
            $deltalat = $flat - $ulat;
            $deltalng = $flng - $ulng;
            $result = (2 * asin(sqrt(pow(sin($deltalat / 2), 2) +
                      cos($ulat) * cos($flat) * pow(sin($deltalng / 2), 2)))) * 6371;
            if ($result <= $data->kilometers && $endplan->diffInDays(Carbon::now()) >= 1) {
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
