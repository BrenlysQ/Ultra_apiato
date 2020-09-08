<?php

namespace App\Containers\Kiu\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Actions\KiuHandler;

class LocalizablesChecker extends ConsoleCommand
{

    protected $signature = 'check:localizables';

    protected $description = 'Check status of Kiu Localizables';

    public $kiu;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $this->kiu = new KiuHandler();
      $mail = $this->kiu->checkLocalizables();
      dump($mail);
    }
}
