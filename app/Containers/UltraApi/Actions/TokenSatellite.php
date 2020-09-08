<?php
namespace App\Containers\UltraApi\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\UltraApi\Tasks\SatelliteTokenTask;

class TokenSatellite extends Action{
  public function run(){
    $token = $this->call(SatelliteTokenTask::class);
    return $token;
  }
}
