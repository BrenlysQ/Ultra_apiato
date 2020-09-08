<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;


class GetRankedFreelancesTask extends Task
{
    public function run()
    {
        $freelances = FreelanceFeaturesModel::with('freelance')
                                    ->orderBy('ranking', 'desc')
                                    ->take(9)
                                    ->get();
	    return $freelances;
    }

}
