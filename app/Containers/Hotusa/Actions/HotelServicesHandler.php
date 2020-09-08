<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;

class HotelServicesHandler extends Action {
  public $code_serv;
  public $desc_serv;

  public function __construct($service) {
    $this->code_serv = $service->codigo_servicio;
    $this->desc_serv = $service->desc_serv;
  }

}