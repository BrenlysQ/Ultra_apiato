<?php

namespace App\Containers\Itineraries\Models;
use App\Ship\Parents\Models\Model;

class AirlineBalanceModel extends Model
{
  protected $table = "airlines_balance";
  protected $fillable = [
    'id','airline_code', 'airline_name', 'balance'
  ];
  protected $dates = ['deleted_at'];
}
