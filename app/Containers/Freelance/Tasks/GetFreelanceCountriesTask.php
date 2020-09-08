<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Freelance\Models\FreelanceModel;



class GetFreelabceCountriesTask extends Task
{
    public function run()
    {
      $zones = FreelanceModel::select('country')
                                ->distinct('country')
                                ->get();
	    return $zones;
    }

}
