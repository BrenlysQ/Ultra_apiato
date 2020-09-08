<?php
namespace App\Containers\Hotusa\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Hotusa\Actions\HotelHandler;
use App\Containers\Hotusa\Tasks\GetAirport;
use Illuminate\Support\Facades\Input;
class HotelOptions extends Action{

  public $options = array();
  public $avg_price;
  public $tagpu;
  public $airport;
  public function __construct($response, $tagpu, $payload) {
    $this->tagpu = $tagpu;
    $count = 0;
    $total = 0;
	$max_age = $this->maxAge();

    if(is_array($response->param->hotls->hot)){
      foreach ($response->param->hotls->hot as $key => $hotel) {
		if($max_age <= $hotel->enh){
			$option = new HotelHandler($hotel, $key);
			$this->options[] = $option;
			$count++;
			$total += $option->paxes->price->GlobalFare->TotalAmount;
		}

      }
    }else{
		if($max_age <= $response->param->hotls->hot->enh){
		  $option = new HotelHandler($response->param->hotls->hot,1);
		  $this->options[0] = $option;
		  $count = 1;
		  $total = $option->paxes->price->GlobalFare->TotalAmount;
		}
    }
    $this->avg_price = $total / $count;
    $this->sortOptions();
    $this->airport = (new GetAirport())->run($payload);
  }
  private function maxAge(){//GET MAX PAX AGE
	$rooms_data = Input::get('rooms_data','');
	$maxage = 0;
	foreach($rooms_data as $room){
		if($room['C'] > 0){
			foreach($room['AG'] as $age){
				if($age > $maxage){
					$maxage = $age;
				}
			}
		}
	}
	return $maxage;
  }
  public function sortOptions(){
    usort($this->options, function($a, $b)
    {
      return $a->paxes->price->GlobalFare->TotalAmount > $b->paxes->price->GlobalFare->TotalAmount;
    });
  }
}
