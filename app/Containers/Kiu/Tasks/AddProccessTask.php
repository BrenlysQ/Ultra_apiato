<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Models\KiuNotificationsModel;



class AddProccessTask extends Task
{
    public function run($route)
    {
        $proc = new Process('php artisan kiu:scheduler ' . $route->transaction . ' > /dev/null 2>&1 &');
        $proc->setWorkingDirectory(base_path());
		$proc->run();   
		echo 'se ejecuto '. $route->name . '
		';
		sleep(8);
    }

}


