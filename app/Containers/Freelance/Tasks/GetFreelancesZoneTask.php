<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Freelance\Models\FreelanceModel;



class GetFreelancesZoneTask extends Task
{
    public function run($req)
    {	
		if($req->input('flag') == 1){
			$zones = FreelanceModel::select('country')
                                ->distinct('country')
                                ->get();
		}else{
			$zones = FreelanceModel::select('city', 'country')
                                ->distinct('city')
                                ->get();			
		}
	    return $zones;
    }

}
