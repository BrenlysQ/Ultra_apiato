<?php

namespace App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;
class KiuOperation extends Model
{
  protected $table="kiu_operations";
  protected $fillable=[
    'route','currency','last_start','last_end','st'
  ];
  public function routedata(){
    return $this->hasOne('App\Containers\Kiu\Models\KiuRoutesModel','id','route');
  }
}
