<?php

namespace App\Containers\Freelance\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use Artisan;
use Symfony\Component\Process\Process;
use App\Containers\Freelance\Actions\FreelanceHandler;

class ReviewEmailReminder extends ConsoleCommand
{

    protected $signature = 'reviews:remind';

    protected $description = 'Remind Users to Review Freelances';

    public $freelance;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $this->freelance = new FreelanceHandler();
      $mail = $this->freelance->reviewReminder();
      dump($mail);
    }
}
