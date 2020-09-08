<?php

namespace App\Containers\Stripe\Models;

use App\Ship\Parents\Models\Model;

class StripeModel extends Model
{
    protected $table = "pup_stripe";
    protected $fillable = [
    	'charge_id','status','amount','auth_token','response','date','card_type','email'
  	];

  	public function payment(){
      return $this->morphOne('App\Containers\Payments\Models\PaymentModel', 'pgatewaydata');
  	}
}