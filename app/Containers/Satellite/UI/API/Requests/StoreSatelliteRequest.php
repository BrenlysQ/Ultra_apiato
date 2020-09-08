<?php

namespace App\Containers\Satellite\UI\API\Requests;

use App\Ship\Parents\Requests\Request;

class StoreSatelliteRequest extends Request
{

    public function rules()
    {
        return [
          'satellite.name' => 'required',
          'satellite.domain' => 'required|min:4',
          'satellite.email_satellite' => 'required|email',
          'satellite.password' => 'required|min:4',
          'satellite.currency_selected' => 'required',
          'satellite.name_responsable' => 'required',
          'satellite.identification' => 'required|min:6',
          'satellite.email_responsable' => 'required|email',
          'satellite.address' => 'required|min:4',
          'satellite.telephone' => 'required|min:4',
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
