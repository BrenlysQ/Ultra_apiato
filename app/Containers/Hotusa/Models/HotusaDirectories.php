<?php

namespace App\Containers\Hotusa\Models;

use Illuminate\Database\Eloquent\Model;
class HotusaDirectories extends Model
{
  protected $table="hotusa_directories";
  protected $connection = 'pumaster';
  protected $fillable=[
    'hotel_name','country_code','province_code','province_name','cobol_code'
  ];
}
