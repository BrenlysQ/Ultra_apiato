<?php

namespace App\Containers\UltraApi\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\UltraApi\Models\GdsTagsId;
use Carbon\Carbon;

class CleanGdsTagCommand extends ConsoleCommand
{

    protected $signature = 'clean:gds_tag';

    protected $description = 'Clean data from gds_tags_id table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dt = Carbon::now();
        $dt->subMinutes(30);
        GdsTagsId::where('gen_date','<',$dt)->delete();
    }
}