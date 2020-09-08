<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;


class UpdateFreelanceStatusTask extends Task
{
    public function run()
    {
        $email = Input::get('email');
        $freelance = FreelanceModel::where('email', $email)->first();
        $freelance->free_status = 1;
        $freelance->update();
	    return $freelance;
    }

}
