<?php

namespace App\Containers\Kiu\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Actions\KiuNotifications;

class UpdateKiuNotifications extends ConsoleCommand
{

    protected $signature = 'update:notifications';

    protected $description = 'Update kiu notifications if a preocess change';

    public $kiu;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $notifications = (new KiuNotifications())->run();
      return $notifications;
    }
}
