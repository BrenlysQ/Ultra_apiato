<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Insurance\Tasks\MakeRequest;
use Illuminate\Support\Facades\DB;

class GetCountryByIataTask extends Task
{
    public function run($iata)
    {
      //dd($iata);
      $country = DB::connection('pumaster')->select(
        "SELECT
          airports.`name`,
          airports.IATA,
          insurance_countries.countryCode
          FROM
          airports
          INNER JOIN insurance_countries ON airports.country = insurance_countries.countryName
          WHERE
          airports.IATA = '" . $iata . "'");
      //dd($country);
      return $country[0]->countryCode;
    }

}
