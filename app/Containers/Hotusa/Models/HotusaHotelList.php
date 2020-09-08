<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaHotelList extends Model
{
  protected $table="hotusa_hotel_list";
  protected $fillable=[
    'codesthot','codpobhot','hot_codigo','hot_codcobol','hot_coddup','hot_afiliacion'
  ];
}
