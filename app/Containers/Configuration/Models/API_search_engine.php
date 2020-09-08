<?php

namespace App\Containers\Configuration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class API_search_engine extends Model
{
	use SoftDeletes;
    protected $table="api_search_engine";
    protected $fillable=['id','name','created_at','updated_at'];
    protected $dates = ['deleted_at'];
    protected $hidden = array('pivot');
}
