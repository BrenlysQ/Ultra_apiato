<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use SimpleXMLElement;

class RoomsDataTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public $data;
    public function run()
    {
  	$rooms_data = Input::get('rooms_data','');
      //print_r($rooms_data); die;
      $this->data = array();
      $prints = array();
      $numhabs = 0;
	  if(!is_object($rooms_data) and !is_array($rooms_data)) {
       $rooms_data = json_decode($rooms_data);
		}
	  foreach ($rooms_data as $room_data) {
		$footprint = json_encode($room_data);
		$key = array_search($footprint,$prints);
		if($key !== false){
		  $this->data['numhab' . ($key + 1)]++;
		}else{
		  $prints[$numhabs] = $footprint;
		  $this->addRoom($numhabs, (object)$room_data);
		  $numhabs++;
		}
	  }
      return $this->parseData();
    }
    public function parseData(){
      $parsed = '';
      foreach ($this->data as $key => $value) {
        $parsed .= '<' . $key .'>' . $value .'</' . $key .'>
        ';
      }
      return $parsed;
    }
    public function addRoom($key, $room_data){
      //dd($room_data);
      $this->data['numhab' . ($key + 1)] = 1;
      $this->data['paxes' . ($key + 1)] = $room_data->A . '-' . $room_data->C;
      if($room_data->C > 0){
        $this->data['edades' . ($key + 1)] = implode(',',$room_data->AG);
      }
    }
}
