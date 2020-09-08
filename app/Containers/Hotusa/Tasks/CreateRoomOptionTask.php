<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Hotusa\Actions\RoomHandler;
use App\Commons\CommonActions;
class CreateRoomOptionTask extends Task
{
    public $options = array();
    public function run($paxes,$rooms_data)
    {
      if(is_array($paxes)){
        foreach ($paxes as $keyh => $pax) {
          $room_data = $rooms_data[$keyh];
          $this->options[] = new RoomHandler($pax,$room_data);
        }
      }else{
        $room_data = $rooms_data[0];
        $this->options[0] = new RoomHandler($paxes,$room_data);
      }
      return $this->options;
    }
}
