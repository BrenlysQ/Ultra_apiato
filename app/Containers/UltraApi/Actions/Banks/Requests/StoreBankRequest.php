<?php

namespace App\Containers\UltraApi\Actions\Banks\Requests;

use App\Ship\Parents\Requests\Request;

class StoreBankRequest extends Request
{

    public function rules()
    {
        return [
          'name' => 'required',
          'account_type' => 'required|min:4',
          'account_number' => 'required|min:6',
          'rif' => 'required|min:5',
          'email' => 'required|email',
          'idcurrency' => 'required|exists:api_currencies,id'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
