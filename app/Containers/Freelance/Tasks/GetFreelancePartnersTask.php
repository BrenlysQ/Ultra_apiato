<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Intervention\Image\ImageManagerStatic as Image;


class GetFreelancePartnersTask extends Task
{
    public function run($req)
    {
        $satellite = $req->input('satellite');
        $email = $req->input('email');
        $partners = FreelanceModel::where('id_satellite', $satellite)
                                    ->where('email', '!=', $email)->get();
	    return $partners;
    }

}
