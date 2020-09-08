<?php

namespace App\Containers\UltraApi\Actions\Currencies\Requests;

use App\Ship\Parents\Requests\Request;

class AsgCurrReq extends Request
{

    public function rules()
    {
        return [
          'idcurrency' => 'required|exists:api_currencies,id',
          'iduser' => 'required|exists:users,id'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
