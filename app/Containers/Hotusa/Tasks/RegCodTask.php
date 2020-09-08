<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class RegCodTask extends Task
{

    public function run($reg_cod)
    {
      switch ($reg_cod) {
        case 'RO':
          return 'Sólo alojamiento';
          break;
        case 'OB':
          return 'Sólo alojamiento';
          break;
        case 'SA':
          return 'Sólo alojamiento';
          break;
        case 'BB':
          return 'Alojamiento y desayuno';
          break;
        case 'AD':
          return 'Alojamiento y desayuno';
          break;
        case 'HB':
          return 'Media pensión';
          break;
        case 'MP':
          return 'Media pensión';
          break;
        case 'FB':
          return 'Pensión completa';
          break;
        case 'PC':
          return 'Pensión completa';
          break;
        case 'AI':
          return 'Todo Incluido';
          break;
        case 'TI':
          return 'Todo Incluido';
          break;
        default:
          return '';
          break;
      }
    }

}
