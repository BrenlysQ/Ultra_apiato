<?php

namespace App\Containers\Payments\Gateways\CreditCard\Model;

use App\Ship\Parents\Models\Model;

class PagueloFacilModel extends Model
{
  protected $table = "pup_paguelofacil";
  protected $fillable = [
    'codoper','status','amount','auth_token','response','date','time','cardtype','name','lastname','email'
  ];
  public function payment(){
      return $this->morphOne('App\Containers\Payments\Models\PaymentModel', 'pgatewaydata');
  }
}
