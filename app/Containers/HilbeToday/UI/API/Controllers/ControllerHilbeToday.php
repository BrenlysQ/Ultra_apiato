<?php

namespace App\Containers\HilbeToday\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\HilbeToday\Actions\HilbeTodayHandler;
class ControllerHilbeToday extends ApiController {

	function getPrice() {
		return HilbeTodayHandler::showDollarPrice();
	}
}