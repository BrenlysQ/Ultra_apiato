<?php

namespace App\Containers\UltraApi\Actions\Sabre\Requests;

use App\Ship\Parents\Requests\Request;

class BookingCacheRequest extends Request
{

    public function rules()
    {
        return [
          'tagpu' => 'required|exists:gds_tags_id,tag_pu',
          'legs' => 'required'
        ];
    }

    public function authorize()
    {
        return $this->check([
            // ..
        ]);
    }
}
