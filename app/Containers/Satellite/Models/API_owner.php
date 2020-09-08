<?php

namespace App\Containers\Satellite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class API_owner extends Model
{
	use SoftDeletes;
    protected $table="api_owner";
    protected $fillable=['id','name','identification','email','address','telephone','created_at','updated_at'];
    protected $dates = ['deleted_at'];
}
