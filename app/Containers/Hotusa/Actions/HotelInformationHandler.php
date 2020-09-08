<?php

namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\HotelServicesHandler;
use App\Containers\Hotusa\Actions\HabitationServicesHandler;
use App\Containers\Hotusa\Actions\HotelObservationsHandler;
use App\Containers\Hotusa\Actions\HotelPoliciesHandler;

class HotelInformationHandler extends Action {
  public $cobol_code;
  public $hotel_name;
  public $province;
  public $category;
  public $mail;
  public $web;
  public $phone;
  public $photos = array();
  public $description;
  public $htoarrive = array();
  public $hotel_type;
  public $checkin;
  public $checkout;
  public $address;
  public $logo_h;
  public $age_min_kid;
  public $age_max_kid;
  public $currency;
  public $services_hot = array();
  public $services_hab = array();
  public $lon;
  public $lat;
  public $salons;
  public $observations = array();
  public $policies = array();
  /*public $city_tax;*/

  public function __construct($hotel) {
    //dd($hotel);
    $this->cobol_code = $hotel->hotel_information->codigo_hotel;
    $this->hotel_name = $hotel->hotel_information->nombre_h;
    $this->province = $hotel->hotel_information->provincia;
    $this->category = $hotel->hotel_information->categoria;
    $this->mail = $hotel->hotel_information->mail;
    $this->web = $hotel->hotel_information->web;
    $this->phone = $hotel->hotel_information->telefono;
    if(!is_null($hotel->hotel_information->fotos)){
      if(is_array($hotel->hotel_information->fotos->foto)){
        foreach ($hotel->hotel_information->fotos->foto as $key_photo => $photo) {
          $this->photos[] = $photo;
        }
      }else{
        $this->photos[0] = $hotel->hotel_information->fotos->foto;
      }
    }
    $this->address = $hotel->hotel_information->direccion;
    $this->description = $hotel->hotel_information->desc_hotel;
    //print_r($hotel->hotel_information); die;
    $htoarrive = explode('*', $hotel->hotel_information->como_llegar);
    foreach($htoarrive as $key => $arrive){
      if(!empty($arrive)){
        $this->htoarrive[] = $arrive;
      }
    }
    //= $hotel->hotel_information->como_llegar;
    $this->logo_h = $hotel->hotel_information->logo_h;
    $this->checkin = $hotel->hotel_information->checkin;
    $this->checkout = $hotel->hotel_information->checkout;
    $this->hotel_type = $hotel->hotel_information->tipo_establecimiento;
    $this->age_min_kid = $hotel->hotel_information->edadnindes;
    $this->age_max_kid = $hotel->hotel_information->edadninhas;
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
    $this->lon = $hotel->hotel_information->longitud;
    $this->lat = $hotel->hotel_information->latitud;

    /*
      Aqui empiezan las observaciones,
      que son requeridas para la reserva
    */
  //return $hotel->hotel_observations;
  if($hotel->hotel_observations->num > 0){
    if(is_array($hotel->hotel_observations->observacion)){
      foreach ($hotel->hotel_observations->observacion as $key_obs => $observacion) {
      $this->observations[] = new HotelObservationsHandler($observacion);
      }
    }else{
      $this->observations[0] = new HotelObservationsHandler($hotel->hotel_observations->observacion);
    }
  }

    /*
      Aqui empiezan las politicas,
      que son requeridas para la reserva
    */
     //print_r($hotel); die;
    /*if(is_array($hotel->hotel_policies->politicaCanc)){
      foreach ($hotel->hotel_policies->politicaCanc as $key_poli => $politicaCanc) {
        $this->policies[] = new HotelPoliciesHandler($politicaCanc);
      }
    }else{
      $this->policies[0] = new HotelPoliciesHandler($hotel->hotel_policies->politicaCanc);
    }*/

  }

}
