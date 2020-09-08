<?php

namespace App\Containers\Payments\UI\Web\Requests;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Ship\Parents\Requests\Request;

class ViewPaymentRequest extends Request
{

    public function rules()
    {
      return [
        'invoice' => 'required|exists:api_invoices,id',
      ];
    }

    public function authorize()
    {
        return true;
        // return InvoicesHandler::HasPermission(request()->invoice);
    }
}
