<?php

namespace App\Containers\UltraApi\UI\API\Requests;

use App\Ship\Parents\Requests\Request;

class StoreRouteRequest extends Request
{

    public function rules()
    {
        return [
          'origin' => 'required|exists:pumaster.airports,iata',
          'destination' => 'required|exists:pumaster.airports,iata',
          'se' => 'required|exists:api_search_engine,id'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
