<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Tasks\CreateRoomOptionTask;

use App\Containers\Hotusa\Tasks\PaxHabTask;
class HotelHandler extends Action {
  public $cobol_code;
  public $hotel_name;
  public $province;
  public $category;
  /*public $idate;
  public $odate;*/
  public $pdr;
  public $rooms = array();
  public $paxes;
  public $age_min_kid;
  public $age_max_kid;
  public $address;
  public $desc;
  public $htoarrive;
  public $thumbnail;
  public $photo;
  public $lon;
  public $lat;
  public $infowindow;
  public $city_tax;
  public $hotel_type;
  public $seqnumber;

  public function __construct($hotel,$seqnumber,$withrooms = false, $payload = false) {
	  //print_r($payload);
    $this->cobol_code = $hotel->cod;
    $this->hotel_name = $hotel->nom;
    $this->province = $hotel->prn;
    $this->category = $hotel->cat;

    /*$this->idate = $hotel->fen;
    $this->odate = $hotel->fsa;*/

    $this->pdr = $hotel->pdr;
    $this->paxes = $this->call(PaxHabTask::class,[$hotel->res->pax]);
    if($withrooms){
      $this->rooms[] = $this->call(CreateRoomOptionTask::class,[$hotel->res->pax,$payload->rooms_data]);
     }
    $this->age_min_kid = $hotel->end;
    $this->age_max_kid = $hotel->enh;
    $this->address = $hotel->dir;
    $this->desc = $hotel->desc;
  $htoarrive = explode('*', $hotel->como_llegar);
  $count = 0;
  foreach($htoarrive as $key => $arrive){
    if($key > 0 && $key < 3){
      $this->htoarrive[] = $arrive;
      $count++;
    }elseif($key == 0 && count($htoarrive) == 1){
      $this->htoarrive[] = $arrive;
    }elseif($count == 3){
      break;
    }
  }
    $this->thumbnail = $hotel->thumbnail;
    $this->photo = $hotel->foto;
    $this->lon = $hotel->lon;
    $this->lat = $hotel->lat;
    (property_exists($hotel,'city_tax')) ? $this->city_tax = $hotel->city_tax : $this->city_tax = '';
    //$this->city_tax = $hotel->city_tax;
    $this->hotel_type = $hotel->tipo_establecimiento;
    $this->seqnumber = $seqnumber + 1;

  }

}
