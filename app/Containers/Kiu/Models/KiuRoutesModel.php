<?php

namespace App\Containers\Kiu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class KiuRoutesModel extends Model
{
  use SoftDeletes;
  protected $table="kiu_airline_routes";
  protected $fillable=[
    'origin','destination','footprint','direct',
    'last_transaction','last_completed','trans_st','national'
  ];
  protected $dates = ['deleted_at'];
  public function transaction(){
    return $this->hasOne('App\Containers\Kiu\Models\CacheTrans','route','id');
  }
  public function policies(){
    return $this->hasMany('App\Containers\Kiu\Models\AlPoliciesModel','idroute','id');
  }
}
