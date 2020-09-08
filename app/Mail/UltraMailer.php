<?php

namespace App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class UltraMailer extends Model
{
    protected $table = "ultramailer";
    protected $fillable = ['name','email'];
}
