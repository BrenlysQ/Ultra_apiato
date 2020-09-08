<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Satellite\Models\API_satellite;


class DeleteFreelanceTask extends Task
{
    public function run()
    {
        $id = Input::get('id');
        if (Input::get('type') == 'FREELANCE') {
            $freelance = FreelanceModel::findOrFail($id);
            $freelance->delete();            
        } elseif(Input::get('type') == 'SATELLITE') {
            $freelance = API_satellite::findOrFail($id);
            $freelance->delete();
        }        
	    return $freelance;
    }

}
