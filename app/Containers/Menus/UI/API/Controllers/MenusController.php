<?php

namespace App\Containers\Menus\UI\API\Controllers;

use Illuminate\Http\Request;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Menus\Actions\MenuHandler;

class MenusController extends ApiController
{
  
  	 public function fullMenus()
  	{ //dd("marico hola C");
    	return (new MenuHandler())->fullMenus();
    }

     public function fullMenusRoles()
  	{
    	return (new MenuHandler())->fullMenusRoles();
    }

   //  public function AssignMenuRol()
  	// {
   //  	return (new MunuHandler())->assignMenusRoles());
   //  }
}
