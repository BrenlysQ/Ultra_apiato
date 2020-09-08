<?php

namespace App\Containers\HilbeToday\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\HilbeToday\Actions\HilbeTodayHandler;

class UpdateDollarPrice extends ConsoleCommand
{

    protected $signature = 'dollarprice:update';

    protected $description = 'Update Dollar Price';

    public function __construct()
    {
        parent::__construct();
    }
  
    public function handle()
    {
      $dollar = HilbeTodayHandler::getDollarPrice();
      dump($dollar);
    }
}