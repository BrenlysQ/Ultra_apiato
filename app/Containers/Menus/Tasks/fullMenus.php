<?php

namespace App\Containers\Menus\Tasks;

use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Tasks\Task;
use App\Containers\Menus\Models\MenuAdmin;
use App\Containers\Menus\Models\RolesHasMenu;

class fullMenus extends Task
{
    public function run() {
		// dd("marico hola T");
		$id = Input::get('id');
		$nombre = Input::get('name');
		$parent = Input::get('parent');
	
	
      $menu = new MenuAdmin();
      $menu->id = $id;
      $menu->name = $nombre;
      $menu->parent = $parent;      
      
      $menu->save();
      //dd($Sms);
    return $menu;
    }
}
// $rol =  Role:: select('id')->get();
      // $id_rol= $rol;

      // $menus =MenuAdmin::select('id')->get(); 

       // $id_rol= get('id');
      // $id_menu = Input::get('id_menu');
      // if ($rol==1) {

    //     $menu = RolesHasMenu();

    //     foreach ($menus as $item) {

    //       $menu->id_menu = $item;
          
    //     }
    //     $menu->id_rol = $id_rol;
        //$menu->id_menu =
      // }
        //according to rol assign id_menu
        
        
       // $menu->save();
      //dd($Sms);    