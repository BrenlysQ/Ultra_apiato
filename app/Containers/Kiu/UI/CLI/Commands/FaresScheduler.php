<?php

namespace App\Containers\Kiu\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use App\Containers\Kiu\Models\KiuRoutesModel;
use App\Containers\Kiu\Actions\CacheTransHandler;
use Artisan;
use Symfony\Component\Process\Process;
class FaresScheduler extends ConsoleCommand
{

    protected $signature = 'kiu:scheduler';

    protected $description = 'Schedule Get KIU fares';
    protected $spacer = '
';

    public function __construct()
    {
        parent::__construct();
    }
    public function Start($operation){
      $route = $operation->routedata;
      ($route->direct == 1) ? $direct = " --direct" : $direct = '';
      $transaction = CacheTransHandler::New($operation);
      $command = 'php artisan kiu:fares ' . $transaction->id .
      ' ' . $route->origin . ' ' . $route->destination. ' ' . $direct;
      $proc = new Process($command . ' > /dev/null 2>&1 &');
      $proc->setWorkingDirectory(base_path());
      $proc->run();
      return $command;
    }
    public function handle()
    {
      $procnumber = 12;
      $operations = CacheTransHandler::GetNextRoutes($procnumber);
      foreach ($operations as $key => $operation) {
        //$this->start($route);
        echo $this->start($operation) .'
        ';
      }
      // for($i = 0;$i < $procnumber;$i++){
      //   echo $this->start($routes[$i]->route,$routes[$i]->currency) .'
      //   ';
      // }
    }
}
