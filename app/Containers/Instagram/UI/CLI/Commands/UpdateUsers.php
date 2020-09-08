<?php

namespace App\Containers\Instagram\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Instagram\Actions\InstagramHandler;

class UpdateUsers extends ConsoleCommand
{

    protected $signature = 'instagram:users';
    protected $description = 'Get user information from instagram';
    public $instagram;

    public function __construct(InstagramHandler $instagram)
    {
        $this->instagram = $instagram;
        parent::__construct();
    }

    public function handle()
    {
      $this->instagram->updateUsers();
    }
}
