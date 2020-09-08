<?php

namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Tasks\UpdateNotificationsTask;
use App\Containers\Kiu\Tasks\HashJsonProcessesTask;
use App\Containers\Kiu\Tasks\ListRunningProcesTask;
use App\Containers\Kiu\Tasks\GetKiuRoutesWithOperationsTask;
use App\Containers\Kiu\Tasks\AddProccessTask;

class KiuNotifications extends Action {

  public function run(){
        $i = 0;        
        $hash1 = '';
		$limit = 20;
		$routes = $this->call(GetKiuRoutesWithOperationsTask::class,[]);
		//dd($routes);
        while ($i < count($routes)) {
            $list = $this->call(ListRunningProcesTask::class,[]);
            $json = $this->call(HashJsonProcessesTask::class,[$list]);
			/*dd($json);
			echo $json->counter . '
			'; die;*/
			if($json->counter <= ($limit - 4)){
                $this->call(AddProccessTask::class,[$routes[$i]]);
                $i++;
				$list = $this->call(ListRunningProcesTask::class,[]);
				$json = $this->call(HashJsonProcessesTask::class,[$list]);
			}
			$hash = hash('sha256',json_encode($json));
            if ($hash != $hash1) {
                $hash1 = $this->call(UpdateNotificationsTask::class,[$json]);
            }
            sleep(5);
        }
    }
}
?>


