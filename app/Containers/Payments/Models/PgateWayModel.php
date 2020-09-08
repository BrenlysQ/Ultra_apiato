<?php

namespace App\Containers\Payments\Models;

use App\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Firebase\SyncsWithFirebase;

class PgateWayModel extends Model
{
  use SoftDeletes;
  protected $table = "pup_pgateways";
  protected $fillable = [
    'name','route'
  ];
  protected $dates = ['deleted_at'];
  
}
