<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;


class CancellationFeesTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($n_localizador)
    {
      $request = '<?xml version="1.0" encoding="ISO-8859-1" ?>
				<peticion>
				<tipo>142</tipo>
				<nombre>Info gastos de cancelación</nombre>
				<agencia>PLUS ULTRA TRAVEL BUSINESS</agencia>
				<parametros>
				<usuario>D85709</usuario>
				<localizador>' . $n_localizador . '</localizador>
				<idioma>1</idioma>
				</parametros>
				</peticion>';
      return $request;
    }

}
