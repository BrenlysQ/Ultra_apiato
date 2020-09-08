<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Hotusa\Models\HotusaDirectories;
use DB;
use Stevebauman\Location\Facades\Location;
class GetHotelAvailability extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($data, $rooms_data)
    {
    ini_set('memory_limit','1024M');
    $position = Location::get($data->ip)->countryCode;
    $hotels = json_decode(HotusaDirectories::select(DB::raw('cobol_code'))
          ->where('province_code','=',Input::get('provincia'))
          ->get());
     $code = '';
     foreach($hotels as $key => $hotel){
       $code .= '#'.$hotel->cobol_code;
       if($key == 249)
      break;
     }
      $request = '<?xml version="1.0" encoding="ISO-8859-1" ?>
                  <!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_disponibilidad_110.dtd">
                  <peticion>
                  <tipo>110</tipo>
                  <nombre>Disponibilidad Varias Habitaciones Regímenes</nombre>
                  <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <parametros>
                      <hotel>'.$code.'</hotel>
                      <pais>'.$data->pais.'</pais>
                      <provincia>'.$data->provincia.'</provincia>
            <pais_cliente>'.$position.'</pais_cliente>
                      <poblacion></poblacion>
                      <categoria>'.$data->categoria.'</categoria>
                      <radio>'.$data->radio.'</radio>
                      <fechaentrada>'.$data->fechaentrada.'</fechaentrada>
                      <fechasalida>'.$data->fechasalida.'</fechasalida>
                      <marca></marca>
                      <afiliacion>'.getenv('AFILIACION_HOT').'</afiliacion>
                      <usuario>D85709</usuario>
                      '. $rooms_data .'
                      <idioma>'.$data->idioma.'</idioma>
                      <duplicidad>'.$data->duplicidad.'</duplicidad>
                      <comprimido>2</comprimido>
                      <informacion_hotel>1</informacion_hotel>
                      <tarifas_reembolsables>'.$data->tarifas_reembolsables.'</tarifas_reembolsables>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
