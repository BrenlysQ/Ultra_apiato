<?php

namespace App\Containers\UltraApi\Actions\Fees\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeModel extends Model
{
  use SoftDeletes;
  protected $table = "api_user_fee";
  protected $fillable = [
    'id','fee','plusultra','type',
    'currency','user','name'
  ];
  public function currency(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','currency');
  }
  public function user(){
    return $this->hasOne('App\Containers\User\Models\User','id','user');
  }
}
