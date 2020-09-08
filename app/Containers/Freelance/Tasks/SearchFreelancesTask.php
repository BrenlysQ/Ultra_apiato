<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;


class SearchFreelancesTask extends Task
{
    public function run($req)
    {
		$type = $req->input('type');
		$search = $req->input('search');
		if($type == 1){
			//id
			$freelances = FreelanceModel::where('id', $search)
										->with('features')
										->with('bankinfo')
										->get();
		}elseif($type == 2){
			//Region o pais
			$freelances = FreelanceModel::where('city', 'LIKE', '%'.$search.'%')
										->orWhere('country', 'LIKE', '%'.$search.'%')
										->with('features')
										->with('bankinfo')
										->get();
		}elseif($type == 3){
			//Nombre o apellido
			$freelances = FreelanceModel::where('name', 'LIKE', '%'.$search.'%')
										->orWhere('lastname', 'LIKE', '%'.$search.'%')
										->with('features')
										->with('bankinfo')
										->get();
		}

	    return $freelances;
    }

}
