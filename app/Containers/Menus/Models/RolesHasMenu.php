<?php
namespace  App\Containers\Menus\Models;

use Illuminate\Database\Eloquent\Model;

class RolesHasMenu extends Model
{
    protected $connection= 'menusAdmin';
    protected $table='menus_roles';
    protected $fillable=['id_rol', 'id_menu'];

    public function RolesHasMenu(){
        return $this->belongsTo('App\Containers\Menus\Models\MenuAdmin', 'id_rol', 'id_menu');
    }
}
