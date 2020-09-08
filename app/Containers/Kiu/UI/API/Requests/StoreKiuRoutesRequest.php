<?php

namespace App\Containers\Kiu\UI\API\Requests;

use App\Ship\Parents\Requests\Request;

class StoreKiuRoutesRequest extends Request
{

    public function rules()
    {
        return [
          'origin' => 'required|min:3',
          'destination' => 'required|min:3'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}