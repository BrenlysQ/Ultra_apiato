<?php
namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;

class PrebookHandler extends Action{

  public $status;
  public $booking_id;
  public $total_base_amount;
  public $total_currency;
  public $message_number;
  public $file_number;
  public $observations;
  public $info;
  public function __construct($response) {
    $this->status = $response->estado;  
    $this->booking_id = $response->n_localizador;
    $this->total_base_amount = $response->importe_total_reserva;
    $this->total_currency = $response->divisa_total;
    $this->message_number = $response->n_mensaje;
    $this->file_number = $response->n_expediente;
    $this->observations = $response->observaciones;
    $this->info = $response->datos;
  }

}
