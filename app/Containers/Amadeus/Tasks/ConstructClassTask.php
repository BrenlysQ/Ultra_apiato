<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;

class ConstructClassTask extends Task {
	public $classes = array();
	public function run($option, $paxes = false){
		/*echo 'ssss';
		print_r($option); die;*/
		if(!is_array($option->paxFareProduct)){
			$paxFareProduct = $option->paxFareProduct;
		}else{
		    $paxFareProduct = $option->paxFareProduct[0];
		}
		if(is_array($paxFareProduct->fareDetails)){
			foreach($paxFareProduct->fareDetails as $key => $fareDetails){
				if(is_array($fareDetails->groupOfFares)){
					foreach($fareDetails->groupOfFares as $i => $groupOfFares){
						if($key == 0){
							ConstructClassTask::createObject($i,'outbound',$groupOfFares);
						}else{
							ConstructClassTask::createObject($i,'return',$groupOfFares);
						}
					}
				}else{
					if($key == 0){
						ConstructClassTask::createObject(0,'outbound',$fareDetails->groupOfFares);
					}else{
						ConstructClassTask::createObject(0,'return',$fareDetails->groupOfFares);
					}
				}
			}
		}else{
			if(is_array($paxFareProduct->fareDetails->groupOfFares)){
				foreach($paxFareProduct->fareDetails->groupOfFares as $i => $groupOfFares){
					ConstructClassTask::createObject($i,'outbound',$groupOfFares);
				}
			}else{
				ConstructClassTask::createObject(0,'outbound',$paxFareProduct->fareDetails->groupOfFares);
			}
		}
		return $this->classes;
	}
	
	public function createObject($key,$leg,$groupOfFares){
		if(!property_exists($groupOfFares, 'productInformation')){
			$this->classes[$leg][$key] = $groupOfFares->cabinProduct->rbd;
		}else{
			$this->classes[$leg][$key] = $groupOfFares->productInformation->cabinProduct->rbd;
		}
	}
}
