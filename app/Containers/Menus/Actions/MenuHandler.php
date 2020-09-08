<?php
namespace App\Containers\Menus\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Menus\Models\MenuAdmin;
use App\Containers\Menus\Models\RolesHasMenu;
use App\Containers\Menus\Tasks\fullMenus;
use App\Containers\Menus\Tasks\fullMenusroles;


class MenuHandler extends Action {

	public function fullMenus()
	{
			$menu =  (new fullMenus())->run();
			return $menu;
	}

	public function fullMenusRoles()
	{
			$menu =  (new fullMenusRoles())->run();
			return $menu;
	}

	// public function FullCampaignTable()
	// {
	// 		//dd("entra");
	//     	$mails = (new FullCampaignTable())->run();
	//     	//dd("Alloooo bien2");
	//     	return $mails;
	// }
	
}
