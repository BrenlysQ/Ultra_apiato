<?php
namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\PriceHandler;

class BookingBonusHandler extends Action{

  public $fares;
  public $booking;
  public function __construct($response) {
  	$this->booking = $response;
  	if($response->reserva->divisa == 'DO'){
      $currency = 'USD';
    }
    $this->fares = PriceHandler::PriceBuild($response->reserva->imptotres);
  }

}
