<?php

namespace App\Containers\Instagram\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Instagram\Actions\InstagramHandler;
use Illuminate\Support\Facades\Input;
class ControllerInstagram extends ApiController {
	public $instagram;
	public function __construct(){
      $this->instagram = new InstagramHandler();
    }
    function getLikes() {
		return $this->instagram->listLikes();
	}
	function getComments() {
		return $this->instagram->listComments();
	}
	function getMessages(){
		return $this->instagram->listMessages();
	}
	function sendMessage(){
		return $this->instagram->sendMessage();
	}
	function getDefMessages(){
		return $this->instagram->getDefMessages();
	}

	function changeStatus(){
		return $this->instagram->changeStatus();
	}
	function getTimeLine(){
		return response()->json($this->instagram->getTimeLine());
		//return $this->instagram->getTimeLine();
	}
	function listContacts(){
		return $this->instagram->listContacts();
	}
	function redirectClient(){
		return $this->instagram->redirectClient();
	}
	function getNotifications(){
		return $this->instagram->getNotifications();
	}
	function obtainMessages(){
		return $this->instagram->obtainMessages();
	}
}
