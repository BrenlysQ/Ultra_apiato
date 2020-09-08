<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaCountries extends Model
{
  protected $table="hotusa_countries";
  protected $connection = 'pumaster';
  protected $fillable=[
    'country_code','country_name'
  ];
}
