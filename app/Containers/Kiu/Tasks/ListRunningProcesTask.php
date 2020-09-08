<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;



class ListRunningProcesTask extends Task
{
    public function run()
    {
        $proc = new Process('ps aux | grep kiu:fares');
        $proc->setWorkingDirectory(base_path());
        $proc->mustRun();
        $text = $proc->getOutput();       
        return $text;
    }

}
