<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetBookingInfoTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($booking_id)
    {
      $request = '<?xml version="1.0" encoding="ISO-8859-1"?>
                  <!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_inforeserva.dtd">
                  <peticion>
                    <tipo>11</tipo>
                    <nombre>Petición de Información</nombre>
                    <agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
                    <parametros>
                      <Localizador>'. $booking_id .'</Localizador>
                      <Afiliacion>'.getenv('AFILIACION_HOT').'</Afiliacion>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
