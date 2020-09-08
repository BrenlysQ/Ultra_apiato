<?php

namespace App\Containers\UltraSMS\Tasks;
use Mail;
use App\Ship\Parents\Tasks\Task;
use App\Containers\UltraSMS\Models\UltraSms;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetDataSmsTask extends Task
{
    public function run() {
        $recipients = UltraSms::orderBy('id', 'ASC')->get();
        $data = json_decode($recipients);
        return $data;
    }
	
}
