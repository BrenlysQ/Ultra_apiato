<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;

class CancelReservationTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($loc_long,$loc_short)
    {
      $request = '<?xml version="1.0" encoding="ISO-8859-1" ?>
			<!DOCTYPE peticion SYSTEM " http://xml.hotelresb2b.com/xml/dtd/pet_cancelacion.dtd">
			<peticion>
			<nombre>Cancelacion Reserva</nombre>
			<agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
			<tipo>401</tipo>
			<parametros>
			<localizador_largo>' . $loc_long . '</localizador_largo>
			<localizador_corto>' . $loc_short . '</localizador_corto>
			</parametros>
			</peticion>';
      return $request;
    }

}
