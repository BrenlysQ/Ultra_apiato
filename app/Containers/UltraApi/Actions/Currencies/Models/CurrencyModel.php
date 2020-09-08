<?php

namespace App\Containers\UltraApi\Actions\Currencies\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyModel extends Model
{
  use SoftDeletes;
  protected $table = "api_currencies";
  protected $fillable = ['id','name','country','code','pcc','default','code_visible'];
  //protected $dates = ['deleted_at'];
  protected $hidden = array('pivot');
  
  public function pgateways()
  {
      return $this->belongsToMany('App\Containers\Payments\Models\PgateWayModel','pup_gateways_currencies','idcurrency','idgateway');
  }
  public function banksinfo()
  {
      return $this->hasMany('App\Containers\UltraApi\Actions\Banks\Models\ModelBanksTable','idcurrency','id');
  }
}
