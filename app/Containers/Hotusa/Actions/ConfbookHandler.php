<?php
namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;

class ConfbookHandler extends Action{

  public $status;
  public $booking_id;
  public $short_booking_id;
  public function __construct($response) {
    $this->status = $response->estado;  
    $this->booking_id = $response->localizador;
    $this->short_booking_id = $response->localizador_corto;
  }

}
