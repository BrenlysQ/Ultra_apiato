<?php

namespace App\Containers\Menus\UI\CLI\Commands;

use Artisan;
use Carbon\Carbon;
use App\Commons\Logger;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\Menus\Actions\MenusHandler;

class MenusCommand extends ConsoleCommand
{

    protected $signature = 'Menus:send';

   // protected $description = 'Send masive emails';

    public function __construct()
    {
        parent::__construct();
    }

    // public function handle()
    // {
    //   (new MenusHandler)->manageRequest();
    // }
}
