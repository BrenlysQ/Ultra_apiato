<?php

namespace App\Containers\UltraApi\Actions\Currencies\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeModel extends Model
{
  use SoftDeletes;
  protected $table = "api_currencies";
  protected $fillable = ['id','name','country','code'];
  //protected $dates = ['deleted_at'];
}
