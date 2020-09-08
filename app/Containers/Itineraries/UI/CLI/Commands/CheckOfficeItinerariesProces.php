<?php

namespace App\Containers\Itineraries\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Itineraries\Actions\ItinerariesHandler;

class CheckOfficeItinerariesProces extends ConsoleCommand
{

    protected $signature = 'office:localizables';

    protected $description = 'Check localizables made by the offices to know if they are emited, canceled or waiting.';

    public $itineraries;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $this->itineraries = new ItinerariesHandler();
      $mail = $this->itineraries->checkOfficeItineraries();
      dump($mail);
    }
}
