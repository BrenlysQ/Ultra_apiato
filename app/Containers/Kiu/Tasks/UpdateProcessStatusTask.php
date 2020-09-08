<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Models\ProcessModel;



class UpdateProcessStatusTask extends Task
{
    public function run()
    {
      $current = ProcessModel::where('status', 1)->first();
      $last = ProcessModel::orderBy('id', 'desc')->first();
      if ($current == $last) {
        $current->status = 0;
        $current->update();
        $current = ProcessModel::first();
        $current->status = 1;
        $current->update();
      }else {
        $current->status = 0;
        $current->update();
        $current = $current->next();
        $current->status = 1;
        $current->update();
      }
      return json_encode($current);
    }

}
