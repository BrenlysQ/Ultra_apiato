<?php

namespace App\Containers\Sabre\UI\Api\Requests;

use App\Ship\Parents\Requests\Request;

class BookingRequest extends Request
{

    public function rules()
    {
      return [
        'tagpu' => 'required|exists:gds_tags_id,tag_pu',
        'legs' => 'required|json',
        'datapaxes' => 'required|json',
        "usersat" => 'required|json',
        "pgateway" => 'required|exists:pup_pgateways,id'
      ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
