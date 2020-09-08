<?php

namespace App\Containers\Payments\Models;

use App\Ship\Parents\Models\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class PaymentModel extends Model
{
  protected $table = "pup_payments";
  protected $fillable = [
    'idinvoice','amount','sat_userdata','idcurrency','customer','payment_gateway',
    'satellite','sat_userid','st','payment_gateway','processedby'
  ];
  public function invoice(){
      return $this->hasOne('App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel','id','idinvoice');
  }
  public function currency(){
      return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','idcurrency');
  }
  public function getSatUserdataAttribute($value){
    return json_decode($value);
  }
  public function satellite(){
      return $this->hasOne('App\Containers\User\Models\User','id','satellite');
  }
  public function satellite_token(){
      return $this->hasOne('App\Containers\Satellite\Models\API_satellite','id','satellite');
  }
  public function pgateway(){
      return $this->hasOne('App\Containers\Payments\Models\PgateWayModel','id','payment_gateway');
  }
  public function customer(){
      return $this->hasOne('App\Containers\User\Models\User','id','customer');
  }
  public function processor(){
      return $this->hasOne('App\Containers\User\Models\User','id','processedby');
  }
  public function pgatewaydata(){
      return $this->morphTo();
  }
}
