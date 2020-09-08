<?php

namespace App\Containers\UltraApi\Models;

use Illuminate\Database\Eloquent\Model;

class ModelAuthTable extends Model
{
  protected $table="external_auths";
  protected $fillable=['id','title','auth','details'];
}
