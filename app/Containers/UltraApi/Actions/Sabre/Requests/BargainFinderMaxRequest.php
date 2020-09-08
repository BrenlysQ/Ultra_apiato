<?php

namespace App\Containers\UltraApi\Actions\Sabre\Requests;

use App\Ship\Parents\Requests\Request;

class BargainFinderMaxRequest extends Request
{

    public function rules()
    {
        return [
          'departure_city' => 'required|exists:pumaster.airports,iata',
          'destination_city' => 'required|exists:pumaster.airports,iata',
          'departure_date' => 'required|date',
          "return_date" => 'required|date',
          "adult_count" => 'integer',
          "child_count" => 'integer',
          "inf_count" => 'integer',
          "currency" => 'required|exists:api_currencies,id'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
