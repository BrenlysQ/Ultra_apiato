<?php

namespace  App\Containers\Menus\Models;

use Illuminate\Database\Eloquent\Model;

class MenuAdmin extends Model
{
    protected $connection= 'menusAdmin';
    protected $table='menus';
    protected $fillable=['id', 'name', 'parent'];
    
}