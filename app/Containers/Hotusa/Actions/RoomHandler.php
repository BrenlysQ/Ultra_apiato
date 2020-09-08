<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\HabRegHandler;
use App\Commons\CommonActions;
class RoomHandler extends Action {
  public $rooms = array();
  public $pax_code;
  public $pax_ocupation;
  public function __construct($pax,$room_data) {
    //print_r($pax); die;
    $this->pax_code = $pax->cod;
    //$this->cod_room = $pax->hab->cod;
    $this->pax_ocupation = $room_data;
    $habitacion = $pax->hab;
    //$this->desc_room = ucfirst(strtolower($pax->hab->desc));
    //print_r($pax); die;
    if(is_array($habitacion)){
      foreach ($habitacion as $key_room => $room) {
        $this->rooms[] = $this->parseReg($room,$key_room);
      }
    }else{
      $this->rooms[0] = $this->parseReg($habitacion,1);
    }
    //$this->sortOptions();
  }
  public function parseReg($habitacion,$seqnum){
    $room = CommonActions::CreateObject();
    $room->cod_room = $habitacion->cod;
    $room->desc_room = ucfirst(strtolower($habitacion->desc));
    $room->reg = array();
    $room->seqnumber = $seqnum;
    if(is_array($habitacion->reg)){
      foreach ($habitacion->reg as $key_reg => $regimen) {
        $room->reg[] = new HabRegHandler($regimen,$key_reg + 1);
      }
    }else{
      $room->reg[0] = new HabRegHandler($habitacion->reg,1);
    }
    $this->sortOptions($room->reg);
    return $room;
  }
  public function sortOptions(&$reg){
    usort($reg, function($a, $b)
    {
      return $a->reg_prr->GlobalFare->TotalAmount > $b->reg_prr->GlobalFare->TotalAmount;
    });
  }
}
