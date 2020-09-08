<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\HotelServicesHandler;
use App\Containers\Hotusa\Actions\HabitationServicesHandler;

class CardInformationHandler extends Action {
  public $mail;
  public $web;
  public $phone;
  public $photos = array();
  public $hotel_type;
  public $checkin;
  public $checkout;
  public $logo_h;
  public $currency;
  public $services_hot = array();
  public $services_hab = array();
  public $salons;

  public function __construct($hotel) {
    //dd($hotel);
    $this->mail = $hotel->hotel_information->mail;
    $this->web = $hotel->hotel_information->web;
    $this->phone = $hotel->hotel_information->telefono;

    if(is_array($hotel->hotel_information->fotos->foto)){
      foreach ($hotel->hotel_information->fotos->foto as $key_photo => $photo) {
        $this->photos[] = $photo;
      }
    }else{
      $this->photos[0] = $hotel->hotel_information->fotos->foto;
    }

    $this->logo_h = $hotel->hotel_information->logo_h;
    $this->checkin = $hotel->hotel_information->checkin;
    $this->checkout = $hotel->hotel_information->checkout;
    $this->hotel_type = $hotel->hotel_information->tipo_establecimiento;
    $this->currency = $hotel->hotel_information->currency;

    if(!is_null($hotel->hotel_information->servicios)){
      if((is_array($hotel->hotel_information->servicios->servicio)) && (!is_null($hotel->hotel_information->servicios->servicio))){
        foreach ($hotel->hotel_information->servicios->servicio as $key_service => $service) {
          /*$this->services_hot[] = $service;*/
          $this->services_hot[] = new HotelServicesHandler($service);
        }
      }elseif((!is_null($hotel->hotel_information->servicios->servicio))) {
        $this->services_hot[0] = new HotelServicesHandler($hotel->hotel_information->servicios->servicio);
      }
    }
    
    /*Estos son los servicios de la habitacion */
    if(!is_null($hotel->hotel_information->servicios_habitacion)){
      if(is_array($hotel->hotel_information->servicios_habitacion->servicio_habitacion)){
        foreach ($hotel->hotel_information->servicios_habitacion->servicio_habitacion as $key_hab => $service_hab) {
          $this->services_hab[] = new HabitationServicesHandler($service_hab);
        }
      }else{
        $this->services_hab[0] = new HabitationServicesHandler($hotel->hotel_information->servicios_habitacion->servicio_habitacion);
      }
    }
        
    $this->salons = $hotel->hotel_information->salones;
    
  }

}