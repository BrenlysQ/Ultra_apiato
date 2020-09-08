<?php

namespace App\Containers\Invoice\Models;
use App\Ship\Parents\Models\Model;

class InvoiceStatusModel extends Model
{
  protected $table = "api_invoice_items";
  protected $fillable = [
    'status_name'
  ];

  public function invoices(){
    return $this->hasMany('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel', 'administration_status', 'id' );
  }
}
