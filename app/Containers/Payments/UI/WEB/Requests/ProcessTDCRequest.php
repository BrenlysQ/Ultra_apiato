<?php

namespace App\Containers\Payments\UI\Web\Requests;
use App\Ship\Parents\Requests\Request;

class ProcessTDCRequest extends Request
{

    public function rules()
    {
      return [
        'totpay' => 'required|numeric',
        'invid' => 'required|exists:api_invoices,id',
        'pgateway' => 'required|exists:pup_pgateways,id',
        'name_holder' => 'required',
        'doc_id' => 'required',
        'card_number' => 'required',
        'cvc_number' => 'required|numeric',
        'expiry_date' => 'required'
      ];
    }

    public function authorize()
    {
      return $this->check([
          // ..
      ]);
    }
}
