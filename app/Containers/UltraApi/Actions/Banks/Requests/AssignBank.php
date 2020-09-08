<?php

namespace App\Containers\UltraApi\Actions\Banks\Requests;

use App\Ship\Parents\Requests\Request;

class AssignBank extends Request
{

    public function rules()
    {
        return [
          'bank' => 'required|exists:api_banks,id',
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
