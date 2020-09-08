<?php

namespace App\Containers\UltraApi\Actions\Banks\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelBanksTable extends Model
{
  use SoftDeletes;
  protected $table = "api_banks";
  protected $fillable = [
    'id','name','account_type','rif','email',
    'account_number','img_url','idcurrency','idstatus'
  ];
  protected $dates = ['deleted_at'];
  public function currency(){
    return $this->hasOne('App\Containers\UltraApi\Actions\Currencies\Models\CurrencyModel','id','idcurrency');
  }
}
