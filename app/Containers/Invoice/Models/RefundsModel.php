<?php

namespace App\Containers\Invoice\Models;

use Illuminate\Database\Eloquent\Model;

class RefundsModel extends Model
{
  protected $table="api_refunds";
  protected $fillable=['id', 'invoice_id', 'itinerary_id', 'price', 'balance'];
  protected $dates = ['deleted_at'];

  public function invoice(){
    return $this->belongsTo('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel','invoice_id','id');
  }

  public function itinerary(){
    return $this->belongsTo('App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel', 'itinerary_id', 'id');
  }
}
