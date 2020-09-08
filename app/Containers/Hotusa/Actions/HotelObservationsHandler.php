<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use Carbon\Carbon;

class HotelObservationsHandler extends Action {
  public $text_obs;
  public $from_obs;
  public $to_obs;

  public function __construct($observation) {
    $desde = HotelObservationsHandler::date_format($observation->obs_desde);
    $hasta = HotelObservationsHandler::date_format($observation->obs_hasta);

    $this->text_obs = $observation->obs_texto;
    $this->from_obs = $desde;
    $this->to_obs = $hasta;
  }

  private static function date_format($aux_date){
  	$date = Carbon::parse($aux_date);
    $date = $date->format('d/m/Y');

    return $date;
  }

}