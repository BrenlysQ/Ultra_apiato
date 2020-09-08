<?php

namespace App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;

class CacheTrans extends Model
{
  protected $table="kiu_cache_transactions";
  public $timestamps = false;
  protected $fillable=[
    'pid','classes','route','time_start','time_end',
    'interval_start','interval_end','created','update',
    'currency','st','operation'
  ];
  public function kiuroute(){
    return $this->belongsTo('App\Containers\Kiu\Models\KiuRoutesModel','route','id');
  }
  public function operationdata(){
    return $this->hasOne('App\Containers\Kiu\Models\KiuOperation','id','operation');
  }

}
