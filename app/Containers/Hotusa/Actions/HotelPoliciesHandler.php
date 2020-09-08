<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;

class HotelPoliciesHandler extends Action {
  public $date;
  public $days_in_advance;
  public $hours_in_advance;
  public $night_spending;
  public $stay_spending;
  public $concept;
  public $in_with_spending;

  public function __construct($policies) {
    $this->date = $policies->fecha;
    $this->days_in_advance = $policies->dias_antelacion;
    $this->hours_in_advance = $policies->horas_antelacion;
    $this->night_spending = $policies->noches_gasto;
    $this->stay_spending = $policies->estCom_gasto;
    $this->concept = $policies->concepto;
    $this->in_with_spending = $policies->entra_en_gastos;
  }

}