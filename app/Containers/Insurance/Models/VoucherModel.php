<?php

namespace App\Containers\Insurance\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
  protected $table="insurance_quotations";
  protected $fillable=['response','quotation_id','currency','plan','voucher_id','document_url',
                        'voucher_status', 'destination_city',  'departure_city'];

  public function item()
  {
      return $this->morphOne('App\Containers\Invoice\Models\ItemsModel', 'invoiceable');
  }
  public function getResponseAttribute($value)
  {
  	return json_decode($value);
  }
}
