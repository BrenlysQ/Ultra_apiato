<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Models\KiuRoutesModel;



class GetKiuRoutesWithOperationsTask extends Task
{
    public function run()
    {
        $routes = KiuRoutesModel::with('transaction')->get();
		$parsedRoutes = array();
		foreach($routes as $key => $route){
			$parsedRoutes[$key] = (object) array(
				'route_id' => $route->id,
				'name' => $route->footprint,
				'currency' => $route->transaction->currency,
				'transaction' => $route->transaction->id,
			);
		}
        return $parsedRoutes;
    }

}
