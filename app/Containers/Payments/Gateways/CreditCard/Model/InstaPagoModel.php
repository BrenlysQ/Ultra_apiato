<?php

namespace App\Containers\Payments\Gateways\CreditCard\Model;

use App\Ship\Parents\Models\Model;

class InstaPagoModel extends Model
{
  protected $table = "pup_instapago";
  protected $fillable = [
    'success','insta_id','insta_reference','insta_voucher','insta_response'
  ];
  public function payment(){
      return $this->morphOne('App\Containers\Payments\Models\PaymentModel', 'pgatewaydata');
  }
  public function getInstaResponseAttribute($value){
    return json_decode($value);
  }
  //protected $dates = ['deleted_at'];
}
