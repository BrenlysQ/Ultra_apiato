<?php

namespace App\Containers\UltraSMS\Models;

use Illuminate\Database\Eloquent\Model;

class UltraSms extends Model
{
    protected $table = "ultrasms";
    protected $fillable = ['name','cirif','telf'];
}
