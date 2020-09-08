<?php

namespace App\Containers\Instagram\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Instagram\Actions\InstagramHandler;

class GetDirects extends ConsoleCommand
{

    protected $signature = 'instagram:directs';
    protected $description = 'Get Directs from instagram';
    public $instagram;

    public function __construct(InstagramHandler $instagram)
    {   
        $this->instagram = $instagram;
        parent::__construct();
    }
  
    public function handle()
    {
      $directs = $this->instagram->obtainMessages();
      dump($directs);
    }
}