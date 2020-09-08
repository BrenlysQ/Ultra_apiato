<?php

namespace App\Containers\Payments\UI\WEB\Requests;
use App\Ship\Parents\Requests\Request;

class AddTransferRequest extends Request
{

    public function rules()
    {
      return [
        'totpay' => 'required|numeric',
        'invoiceid' => 'required|exists:api_invoices,id',
        'payment_date' => 'required|date',
        'bankid' => 'required|exists:api_banks,id',
        'confirmation_number' => 'required'
      ];
    }

    public function authorize()
    {
      return $this->check([
          // ..
      ]);
    }
}
