<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetReservationsTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($codigo_cobol,$lins)
    {
		$data_pax = (object)Input::get('data_pax');
      $data = (object) array (
        "nombre_cliente" => $data_pax->pax_name . ' ' . $data_pax->pax_lastname,
        "observaciones" => Input::get('observaciones'),
        "tipo_tarjeta" => Input::get('tipo_tarjeta'),
        "num_tarjeta" => Input::get('num_tarjeta'),
        "cvv_tarjeta" => Input::get('cvv_tarjeta'),
        "mes_expiracion_targeta" => Input::get('mes_expiracion_targeta'),
        "ano_expiracion_targeta" => Input::get('ano_expiracion_targeta'),
        "titular_targeta" => Input::get('titular_targeta'),
        "afiliacion" => Input::get('afiliacion')
      );

      $request = '<?xml version="1.0" encoding="ISO-8859-1"?>
                  <!DOCTYPE peticion SYSTEM "http://xml.hotelresb2b.com/xml/dtd/pet_reserva_3.dtd">
                  <peticion>
                    <nombre>Petición de Reserva</nombre>
                    <agencia>Viajes Rufet</agencia>
                    <tipo>202</tipo>
                    <parametros>
                      <codigo_hotel>'.$codigo_cobol.'</codigo_hotel>
                      <nombre_cliente>'.$data->nombre_cliente.'</nombre_cliente>
                      <observaciones>'.$data->observaciones.'</observaciones>
                      <num_mensaje></num_mensaje>
                      <num_expediente>'.$data->afiliacion.'</num_expediente>
                      <forma_pago>44</forma_pago>
                      <tipo_targeta>'.$data->tipo_tarjeta.'</tipo_targeta>
                      <num_targeta>'.$data->num_tarjeta.'</num_targeta>
                      <cvv_targeta>'.$data->cvv_tarjeta.'</cvv_targeta>
                      <mes_expiracion_targeta>'.$data->mes_expiracion_targeta.'</mes_expiracion_targeta>
                      <ano_expiracion_targeta>'.$data->ano_expiracion_targeta.'</ano_expiracion_targeta>
                      <titular_targeta>'.$data->titular_targeta.'</titular_targeta>
                      <res>';
                      if (is_array($lins)){
                        foreach($lins as $lin){
                          $request .= '
                          <lin>' . $lin . '</lin>';
                        }
                      }else{
                        $request .= '
                          <lin>' . $lins . '</lin>';
                      }
                      $request.='
                      </res>
                    </parametros>
                  </peticion>';
      //dd($request);
      return $request;
    }

}
