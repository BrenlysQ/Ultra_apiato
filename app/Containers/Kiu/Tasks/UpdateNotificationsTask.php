<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Models\KiuNotificationsModel;



class UpdateNotificationsTask extends Task
{
    public function run($json)
    {
		
        $notification = KiuNotificationsModel::where('footprint', 'baabkjakla')->first();		
        $notification->data = json_encode($json);
        $notification->update();
        return $notification;
    }

}
