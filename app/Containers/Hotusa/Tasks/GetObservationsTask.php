<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
/**
 * Class UpdateUserTask.
 *
 */
class GetObservationsTask extends Task
{

    /**
     */
    public function run($codigo_cobol,$entrada,$salida)
    {
      $entrada = Carbon::parse($entrada);
      $entrada = $entrada->format('d/m/Y');

      $salida = Carbon::parse($salida);
      $salida = $salida->format('d/m/Y');

      $request = '<?xml version="1.0" encoding="iso-8859-1"?>
                  <peticion>
                    <tipo>24</tipo>
                    <parametros>
                      <codigo_cobol>'.$codigo_cobol.'</codigo_cobol>
                      <entrada>'.$entrada.'</entrada>
                      <salida>'.$salida.'</salida>
                    </parametros>
                  </peticion>';
      return $request;
    }

}
