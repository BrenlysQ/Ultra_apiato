<?php

namespace App\Containers\UltraApi\Actions\Currencies\Requests;

use App\Ship\Parents\Requests\Request;

class StoreCurrRequest extends Request
{

    public function rules()
    {
        return [
          'name' => 'required|min:4',
          'country' => 'required|min:4',
          'pcc' => 'required|min:3|max:6',
          'code' => 'required|min:3|max:4'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
