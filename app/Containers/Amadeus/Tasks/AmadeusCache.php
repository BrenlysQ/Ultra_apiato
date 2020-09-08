<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;  
use App\Commons\TagsGdsHandler;
use Illuminate\Support\Facades\Cache;
use App\Commons\CommonActions;

class AmadeusCache extends Task {
	public function run(){
		$legs = json_decode(Input::get('legs'));
	    $tag = TagsGdsHandler::GetGdsTag();
			$value = Cache::get($tag->tag_id);
				if($value = Cache::get($tag->tag_id)){
					$time_left = CommonActions::TimeLeft($tag->gen_date);
					$itinerary = json_decode($tag->itinerary);
					$cache = json_decode($value);
					$flagOutbound = false;
					$flagReturn = false;
				if(is_object($cache)){
					foreach($cache->flights as $key => $flight){
						if(isset($flight->return)){
							foreach($flight->outbound as $outbound){
								if($outbound->seqnumber == $legs[0]->seqnumber){
									$out = $outbound;
									$flagOutbound = true;
									$auxOutbound = $key;
								}
							}
							foreach($flight->return as $return){
								if($return->seqnumber == $legs[1]->seqnumber){
									$ret = $return;
									$flagReturn = true;
									$auxReturn = $key;
								}
							}
							if(($flagOutbound && $flagReturn) && ($auxReturn == $auxOutbound)){
								$price = $flight->price;
								break;
							}  
						}else{
							if(isset($flight->outbound)){
								foreach($flight->outbound as $outbound){
									if($outbound->seqnumber == $legs[0]->seqnumber){
										$out = $outbound;
										$flagOutbound = true;
										$ret = false;
									}
								}
								if($flagOutbound){
									$price = $flight->price;
									break;
								} 
							}else{
								$out = false;
								$ret = false;
								$price = $flight->price;
							} 
						}
					}  
				}else{
					foreach($cache as $key => $flight){
						if($flight->odo){
							foreach($flight->odo->outbound as $outbound){
								if($outbound->seqnumber == $legs[0]->seqnumber){
									$out = $outbound;
									$flagOutbound = true;
									$auxOutbound = $key;
								}
							}
							foreach($flight->odo->return as $return){
								if($return->seqnumber == $legs[1]->seqnumber){
									$ret = $return;
									$flagReturn = true;
									$auxReturn = $key;
								}
							}
							if(($flagOutbound && $flagReturn) && ($auxReturn == $auxOutbound)){
								$price = $flight->price;
								break;
							}  
						}
					} 
				}
			$object = CommonActions::CreateObject();
			$object->payload = $itinerary;
			$flight = array(
				"outbound" => $out,
				"return" => $ret,
				"price" => $price
			);
			$json = array(
				"flight" => (object) $flight,
				"itinerary" => $itinerary,
				"currency" => $itinerary->currency,
				"time_left" => $time_left
			);
			return $json;
	    }else{
	      return json_encode(CommonActions::ErrorCache());
	    }
	}
}