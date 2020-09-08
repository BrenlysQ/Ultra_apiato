<?php

namespace App\Containers\Menus\Tasks;

use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Tasks\Task;
use App\Containers\User\Task\GetRolsTask;
use App\Containers\Menus\Models\MenuAdmin;
use App\Containers\Menus\Models\RolesHasMenu;
use App\Containers\User\Models\Role;

class FullMenusRoles extends Task
{
    public function run() {

      $id_rol = Input::get('id_rol');
      $id_menu = Input::get('id_menu');
  
      $menu = new RolesHasMenu();
      $menu->id_rol = $id_rol;
      $menu->id_menu = $id_menu;     
      
      $menu->save();

      // $roles = Role::all();

      // foreach ($roles as $rol) 
      // {
      //     $menuRole = new RolesHasMenu();
      //     $menus = MenuAdmin::all();

      //     if($rol==1)//admin
      //     {
      //         foreach ($menus as $menu) 
      //         {
      //            $menuRole->id_rol = $rol;
      //            $menuRole->id_menu= $menu->id;
      //         }
      //     }
      //     else if($rol==2)//operation
      //     {
      //          $menuRole->id_rol = $rol;
      //     }
      //     else if($rol==3)//seller
      //     {
      //          $menuRole->id_rol = $rol;
      //     }
      // }

      // $menuRole->save();
    return $menu;
    }
}