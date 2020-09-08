<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use App\Containers\Freelance\Models\FreelanceCampaignsModel;
use App\Containers\Freelance\Tasks\GetKilometersCampaignTask;
use DB;




class GetSponsoredFreelanceTask extends Task
{
    public function run($freelance, $campaigns, $latitude, $longitude)
    {
      foreach ($freelance as $key => $free) {
        foreach ($campaigns as $campaign) {
          if ($free->id == $campaign->id_freelance) {
            $data = json_decode($campaign->data);
            if (isset($data->kilometers)) {
              $freelance = (new GetKilometersCampaignTask())->run($freelance,$campaigns,$latitude,$longitude);
              return $freelance;
            }elseif (isset($data->city)) {
              $freelance = (new GetCityCampaignTask())->run($freelance,$campaigns);
              return $freelance;
            }elseif (isset($data->country)) {
              $freelance = (new GetCountryCampaignTask())->run($freelance,$campaigns);
              return $freelance;
            }elseif(isset($data->full)){
              $freelance = (new GetFullCampaignTask())->run($freelance,$campaigns);
              return $freelance;
            }else {
              $freelance = (new GetExpensiveCampaignTask())->run($freelance,$campaigns);
              return $freelance;
            }
          }
        }
      }
    }
}
