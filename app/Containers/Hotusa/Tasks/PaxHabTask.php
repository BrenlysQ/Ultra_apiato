<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Hotusa\Actions\RoomHandler;
use App\Commons\CommonActions;
use App\Containers\Hotusa\Actions\PriceHandler;
use App\Containers\Hotusa\Tasks\RegCodTask;
class PaxHabTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public $paxes;
    public function run($paxes)
    {
      $this->paxes = CommonActions::CreateObject();
      $this->paxes->price = 0;
      //print_r($paxes); die;
      if(is_array($paxes)){
        foreach ($paxes as $pax) {
          $this->parseRoom($pax);
        }
      }else{
        $this->parseRoom($paxes);
      }
      $this->paxes->price = PriceHandler::PriceBuild($this->paxes->price,'USD');
      return $this->paxes;
    }
    public function parseRoom($pax){
      if(is_array($pax->hab)){
        $room = $pax->hab[0];
      }else{
        $room = $pax->hab;
      }
      $reg = CommonActions::CreateObject();
      if(is_array($room->reg)){
        foreach ($room->reg as $regt) {
          if(!property_exists($reg,'prr') || $reg->prr > $regt->prr){
            $reg = $regt;
          }
        }
      }else{
        $reg = $room->reg;
      }
      $reg_desc = (new RegCodTask())->run($reg->cod);
      $this->paxes->rooms[] = ucfirst(strtolower($room->desc)) . ' - ' . $reg_desc;
      $this->paxes->price += $reg->prr;
    }
}
