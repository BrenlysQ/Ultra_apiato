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
use DB;




class GetNearestFreelanceTask extends Task
{
    public function run($req)
    {
      $hasubication = $req->input('haspoint');
      $latitude = $req->input('latitude');
      $longitude = $req->input('longitude');
      $campaigns = FreelanceCampaignsModel::all();
      if ($hasubication == 1){
    	    $freelance = FreelanceModel::selectRaw('*, 111.045 * DEGREES(ACOS(COS(RADIANS('.$latitude.'))
                                     * COS(RADIANS(latitude))
                                     * COS(RADIANS(longitude) - RADIANS('.$longitude.'))
                                     + SIN(RADIANS('.$latitude.'))
                                     * SIN(RADIANS(latitude))))
                                     AS distance_in_km')
										->where('free_status', 1)
                                     ->orderBy('distance_in_km', 'asc')
                                     ->with('features','reviews')
                                     ->take(6)
                                     ->get();
         //$freelance = (new GetSponsoredFreelanceTask())->run($freelance, $campaigns, deg2rad($latitude), deg2rad($longitude));
      }else{
    		$latitude = rand(-50, 350);
    		$longitude = rand(-50,4500);
    		$freelance = FreelanceModel::selectRaw('*, 111.045 * DEGREES(ACOS(COS(RADIANS('.$latitude.'))
                                      * COS(RADIANS(latitude))
                                      * COS(RADIANS(longitude) - RADIANS('.$longitude.'))
                                      + SIN(RADIANS('.$latitude.'))
                                      * SIN(RADIANS(latitude))))
                                      AS distance_in_km')
									  ->where('free_status', 1)
                                      ->orderBy('distance_in_km', 'asc')
                                      ->with('features','reviews')
                                      ->take(6)
                                      ->get();

        //$freelance = (new GetSponsoredFreelanceTask())->run($freelance,$campaigns, deg2rad($latitude), deg2rad($longitude));
      }
      $freelance->load(['satellite' => function($q) {
                         $q->with('user');
                     }]);
      return $freelance;
    }

}
