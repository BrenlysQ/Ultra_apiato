<?php

namespace App\Containers\Instagram\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Instagram\Actions\InstagramHandler;

class GetInteractions extends ConsoleCommand
{

    protected $signature = 'instagram:interactions';
    protected $description = 'Get Likes and Comments from instagram';
    public $instagram;

    public function __construct(InstagramHandler $instagram)
    {
        parent::__construct();
        $this->instagram = $instagram;
    }
  
    public function handle()
    {
      $interactions = $this->instagram->obtainComments();
      dump($interactions);
    }
}