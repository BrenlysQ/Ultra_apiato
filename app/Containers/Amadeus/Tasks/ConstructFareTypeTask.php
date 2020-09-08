<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;

class ConstructFareTypeTask extends Task {
	public $types = array();
	public function run($option, $paxes = false){
		/*echo 'ssss';
		print_r($option); die;*/ 
		if(!is_array($option->paxFareProduct)){
			$paxFareProduct = $option->paxFareProduct;
			if(is_array($paxFareProduct->fareDetails)){
				foreach($paxFareProduct->fareDetails as $key => $fareDetails){
					if(is_array($fareDetails->groupOfFares)){ 
						foreach($fareDetails->groupOfFares as $i => $groupOfFares){
							if($key == 0){
								ConstructFareTypeTask::createObject($i,'outbound',$groupOfFares);
							}else{
								ConstructFareTypeTask::createObject($i,'return',$groupOfFares);
							}
						}
					}else{
						if($key == 0){
							ConstructFareTypeTask::createObject(0,'outbound',$fareDetails->groupOfFares);
						}else{
							ConstructFareTypeTask::createObject(0,'return',$fareDetails->groupOfFares);
						}
					}
				}
			}else{
				if(is_array($paxFareProduct->fareDetails->groupOfFares)){
					foreach($paxFareProduct->fareDetails->groupOfFares as $i => $groupOfFares){
						ConstructFareTypeTask::createObject($i,'outbound',$groupOfFares);
					}
				}else{
						ConstructFareTypeTask::createObject(0,'outbound',$paxFareProduct->fareDetails->groupOfFares);
				}
			}
		}else{
		    $paxFareProducts = $option->paxFareProduct;
			foreach($paxFareProducts as $paxFareProduct){
				if(is_array($paxFareProduct->fareDetails)){
					foreach($paxFareProduct->fareDetails as $key => $fareDetails){
						if(is_array($fareDetails->groupOfFares)){
							foreach($fareDetails->groupOfFares as $i => $groupOfFares){
								if($key == 0){
									ConstructFareTypeTask::createObject($i,'outbound',$groupOfFares);
								}else{
									ConstructFareTypeTask::createObject($i,'return',$groupOfFares);
								}
							}
						}else{
							if($key == 0){
								ConstructFareTypeTask::createObject(0,'outbound',$fareDetails->groupOfFares);
							}else{
								ConstructFareTypeTask::createObject(0,'return',$fareDetails->groupOfFares);
							}
						}
					}
				}else{
					if(is_array($paxFareProduct->fareDetails->groupOfFares)){
						foreach($paxFareProduct->fareDetails->groupOfFares as $i => $groupOfFares){
							ConstructFareTypeTask::createObject($i,'outbound',$groupOfFares);
						}
					}else{
						ConstructFareTypeTask::createObject(0,'outbound',$paxFareProduct->fareDetails->groupOfFares);
					}
				}
			}
		}
		return $this->types;
	}
	
	public function createObject($key,$leg,$fareTypes){
		if(!property_exists($fareTypes, 'productInformation')){
			if(!in_array($fareTypes->fareProductDetail->fareType,$this->types)){
				$this->types[$leg][$key] = $fareTypes->fareProductDetail->fareType;
			}
		}else{
			if(!in_array($fareTypes->productInformation->fareProductDetail->fareType,$this->types)){
				$this->types[$leg][$key] = $fareTypes->productInformation->fareProductDetail->fareType;
			}
		}
	}
}
