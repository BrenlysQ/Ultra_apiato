<?php

namespace App\Containers\UltraSMS\UI\CLI\Commands;

use Artisan;
use Carbon\Carbon;
use App\Commons\Logger;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\UltraSMS\Actions\UltraSMSHandler;

class UltraSMSCommand extends ConsoleCommand
{

    protected $signature = 'ultrasms:send';

    protected $description = 'Send masive sms';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      (new UltraSmsHandler)->SmsRequest();
    }
}
