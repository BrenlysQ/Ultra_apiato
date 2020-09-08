<?php

namespace App\Containers\UltraMailer\Models;

use Illuminate\Database\Eloquent\Model;

class UltraMailer extends Model
{
    protected $table = "aliados";
    protected $fillable = ['nombrePersonal','nombreJuridico','cirif','telf1','telf2','telf3','email','email2','ciudad','orden'];
}
