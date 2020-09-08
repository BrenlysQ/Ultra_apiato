<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\PriceHandler;
use App\Containers\Hotusa\Tasks\RegCodTask;
class HabRegHandler extends Action {
  public $reg_cod;
  public $reg_prr;
  public $reg_curr;
  public $reg_nr;
  public $seqnumber;
  public $lin;
  public function __construct($regimen, $key) {
    $aux = [];
    $reg = $this->call(RegCodTask::class,[$regimen->cod]);
    $this->reg_cod = $reg;
    //$this->reg_prr = $regimen->prr;
    $this->reg_curr = $regimen->div;
    if($regimen->div == 'DO'){
      $currency = 'USD';
    }
    $this->lin = $regimen->lin;
    $this->reg_prr = PriceHandler::PriceBuild($regimen->prr,$currency);
    $this->reg_nr = $regimen->nr;
    $this->seqnumber = $key;
  }

}
