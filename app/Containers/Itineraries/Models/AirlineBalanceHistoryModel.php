<?php

namespace App\Containers\Itineraries\Models;
use App\Ship\Parents\Models\Model;

class AirlineBalanceHistoryModel extends Model
{
  protected $table = "airlines_balance_history";
  protected $fillable = [
    'id','localizable', 'airline_code', 'last_balance', 'balance', 'currency'
  ];
  protected $dates = ['deleted_at'];
}
