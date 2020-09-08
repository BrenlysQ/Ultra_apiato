<?php

namespace App\Containers\UltraMailer\UI\CLI\Commands;

use Artisan;
use Carbon\Carbon;
use App\Commons\Logger;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\UltraMailer\Actions\UltraMailerHandler;

class UltraMailerCommand extends ConsoleCommand
{

    protected $signature = 'ultramailer:send';

    protected $description = 'Send masive emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      (new UltraMailerHandler)->manageRequest();
    }
}
