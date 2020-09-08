<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class ParseCarAvailabilty extends Task
{
    public $options;
    public function run($response)
    {
      set_time_limit(0);
      //dd($response);
	  if($response == null || $response == 'undefined'){
		  return false;
	  }
      $rawObject = json_decode($response);
      //dd($rawObject);
	  if($rawObject == null || $rawObject == 'undefined'){
		  return false;
	  }
      $avails = $rawObject->Body->OTA_VehAvailRateRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail;
      foreach ($avails as $index => $option) {
		//print_r($option); die;
        $type   = $option->VehAvailCore->RentalRate->Vehicle->VehType;
        $price  = $option->VehAvailCore->VehicleCharges;
		$currency = $price->VehicleCharge->CurrencyCode;
		$rph = $option->RPH;
		$vendor = $option->Vendor->CompanyShortName;
		$price = (new PriceBuilderTask())->run($price, $currency);
        $option = $this->getTypeDetails($option->Vendor->Code,$type);
        $this->groups[$type]->options[$index] = (new CarsResponseParsing())->run($option, $price, $vendor, $rph);
      }
      foreach ($this->groups as $group){
        $group = (new CarsOptionCleanerTask())->run($group);
      }
      //dd($this->groups);
      return $this->groups;
    }
    public function getTypeDetails($company, $type){
      $data_company = Cache::remember('CARC-' . $company, 1000000, function() use ($company)
      {
        $req = (new CarDetailsLocationTask())->run($company);
        $response = (new SoapRequestTask())->run($req,'OTA_VehLocDetailLLSRQ');
        return $this->parseLocations($response);
      });
      return $data_company->cars[$type];
    }
    public function parseLocations($response){
      $rawObject = json_decode($response);
      $company = CommonActions::CreateObject();
       if(!property_exists($rawObject->Body,'OTA_VehLocDetailRS')){
          print_r($rawObject); die;
    }
      $company_code = $rawObject->Body->OTA_VehLocDetailRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->VehicleProvider->Code;
      $makes = $rawObject->Body->OTA_VehLocDetailRS->VehAvailRSCore->VehVendorAvails->VehVendorAvail->LocationDetails->AdditionalInfo->Makes->MakeModel;
      foreach ($makes as $key => $car) {
        $company->cars[$car->Type] = $car;
      }
      return $company;
    }
}
