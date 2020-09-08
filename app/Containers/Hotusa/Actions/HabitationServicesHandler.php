<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;

class HabitationServicesHandler extends Action {
  public $code_serv_hab;
  public $desc_serv_hab;

  public function __construct($service) {
    $this->code_serv_hab = $service->codigo_servicio_hab;
    $this->desc_serv_hab = $service->descripciones;
  }

}