<?php

namespace App\Containers\Configuration\Models;

use Illuminate\Database\Eloquent\Model;

class API_search_engine_satellite extends Model
{
    protected $table="api_search_engine_user";
    protected $fillable=['iduser','idsearch_engine','created_at','updated_at'];
}
