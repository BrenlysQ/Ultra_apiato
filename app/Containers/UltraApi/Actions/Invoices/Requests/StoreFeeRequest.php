<?php

namespace App\Containers\UltraApi\Actions\Fees\Requests;

use App\Ship\Parents\Requests\Request;

class StoreFeeRequest extends Request
{

    public function rules()
    {
        return [
          'fee' => 'required|numeric',
          'name' => 'required|min:2',
          'type' => 'required|numeric',
          'currency' => 'required|exists:api_currencies,id',
          'user' => 'required|exists:users,id'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
