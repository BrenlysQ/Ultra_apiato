<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client;
use Amadeus\Client\Params;
use Illuminate\Support\Facades\Log;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\PnrNameChangeOptions;
use Amadeus\Client\RequestOptions\Pnr\Reference;
use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest;
use Amadeus\Client\RequestOptions\Pnr\NameChange\Passenger;
use Amadeus\Client\RequestOptions\Pnr\NameChange\Infant;
use Amadeus\Client\RequestOptions\PnrCancelOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\Pnr\Itinerary;
use Amadeus\Client\RequestOptions\Pnr\Segment;
use Amadeus\Client\RequestOptions\Pnr\Segment\Air;
use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Pnr\Element\Contact;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\QueuePlacePnrOptions;
use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\RequestOptions\Queue;
use App\Commons\CommonActions;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
class RequestModifyPnr extends Task {
	
	public $data;
	public $client;
	public $references = array();
	public $elements   = array();
	
	public function run($data,$client){
		$pnrResult = (new RetrievePNRTask())->run(
			$data->itinerary_id,
			$client
		);
		RequestModifyPnr::obtainTattoos($pnrResult);
		RequestModifyPnr::buildSSR($data,$this->references);
		$pnrReply = $client->pnrAddMultiElements(
			new PnrAddMultiElementsOptions([
				'elements'   => $this->elements,
				'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE //ET: END
			])
		);
		//CommonActions::clientInteraction('SaveModifiedPnr',$client);
		$logoutResponse = $client->securitySignOut();
		if ($pnrReply->status === Result::STATUS_OK) {
			$data->update();
			$response = CommonActions::CreateObject();
			$response->modified = true;
			$response->response = $pnrReply;
			return $response;
		}else{
			$response = CommonActions::CreateObject();
			$response->modified = false;
			$response->response = $pnrReply->messages;
			return $response;
		}
	}
	
	private function buildSSR($data,$references){
		foreach($data->paxes as $key => $pax){
			foreach($references as $i => $reference){
				if(($pax->footprint == $reference->footprint) && ($pax->type != 'inf')){
					$date     = RequestModifyPnr::configDate($pax->dob);
					$name     = RequestModifyPnr::buildName($pax->firstname);
					$lastname = RequestModifyPnr::buildName($pax->lastname);
					$freetextDocs = '----'.$date.'-'.$pax->gender.'--'.$lastname.'-'.$name.'';
					$freetextFoid = 'NI'.$pax->passport;
					RequestModifyPnr::buildDocs($freetextDocs,$reference);
					RequestModifyPnr::buildFoid($freetextFoid,$reference);
				}
			}
		}
		if((int)$data->itinerary->inf_count > 0){
			foreach($references as $reference){
				if($reference->withChild){
					foreach($data->paxes as $key => $pax){
						if(($pax->type == 'inf') && ($reference->infant->footprint == $pax->footprint)){
							$date     = RequestModifyPnr::configDate($pax->dob);
							$name     = RequestModifyPnr::buildName($pax->firstname);
							$lastname = RequestModifyPnr::buildName($pax->lastname);
							$freetextDocs = '----'.$date.'-'.$pax->gender.'--'.($name).'-'.$lastname.'';
							RequestModifyPnr::buildDocs($freetextDocs,$reference);
						}
					}
				}
			}
		}
	}
	private function buildDocs($freetextDocs,$reference){
		$this->elements[] = new ServiceRequest([
			'type' => 'DOCS',
			'status' => ServiceRequest::STATUS_HOLD_CONFIRMED,
			'company' => 'YY',
			'quantity' => 1,
			'freeText' => [
				$freetextDocs,
			],
			'references' => [
				new Reference([
					'type' => 'PT',
					'id'   => $reference->tattoo
				])
			]
		]);
	}
	
	private function buildFoid($freetextFoid,$reference){
		$this->elements[] = new ServiceRequest([
			'type' => 'FOID',
			'status' => ServiceRequest::STATUS_HOLD_CONFIRMED,
			'company' => 'YY',
			'quantity' => 1,
			'freeText' => [
				$freetextFoid,
			],
			'references' => [
				new Reference([
					'type' => 'PT',
					'id' => $reference->tattoo
				])
			]
		]);
	}
	
	private function obtainTattoos($pnrResult){
		if(is_array($pnrResult->response->travellerInfo)){
			foreach($pnrResult->response->travellerInfo as $travellerInfo){
				$pax = CommonActions::CreateObject();
				$pax->tattoo = $travellerInfo->elementManagementPassenger->reference->number;
				$pax->number    = $travellerInfo->elementManagementPassenger->lineNumber;
				if(!is_array($travellerInfo->passengerData)){
					$pax->footprint = RequestModifyPnr::encodePaxes($travellerInfo->passengerData);
					$pax->withChild = false;
				}else{
					$pax->footprint = RequestModifyPnr::encodePaxes($travellerInfo->passengerData[0]);
					$pax->withChild = true;
					$infant = CommonActions::CreateObject();
					$infant->footprint = RequestModifyPnr::encodePaxes($travellerInfo->passengerData[1]);
					$pax->infant = $infant;
				}
				$this->references[] = $pax;
			} 
		}else{
			$pax = CommonActions::CreateObject();
			$pax->tattoo = $pnrResult->response->travellerInfo->elementManagementPassenger->reference->number;
			$pax->number = $pnrResult->response->travellerInfo->elementManagementPassenger->lineNumber;
			if(!is_array($pnrResult->response->travellerInfo->passengerData)){
				$pax->footprint = RequestModifyPnr::encodePaxes($pnrResult->response->travellerInfo->passengerData);
				$pax->withChild = false;
			}else{
				$pax->footprint = RequestModifyPnr::encodePaxes($pnrResult->response->travellerInfo->passengerData[0]);
				$pax->withChild = true;
				$infant = CommonActions::CreateObject();
				$infant->footprint = RequestModifyPnr::encodePaxes($pnrResult->response->travellerInfo->passengerData[1]);
				$pax->infant = $infant;
			}
			$this->references[] = $pax;
		}
	}
	
	private static function encodePaxes($paxesdetails){
		if(is_array($paxesdetails)){
			foreach($paxesdetails as $pax){
				$paxes = md5(
					strtolower($pax->travellerInformation->passenger->firstName).
					strtolower($pax->travellerInformation->traveller->surname).
					$pax->travellerInformation->passenger->type
				);				
			}
		}else{
			$paxes = md5(
				strtolower($paxesdetails->travellerInformation->passenger->firstName).
				strtolower($paxesdetails->travellerInformation->traveller->surname).
				$paxesdetails->travellerInformation->passenger->type
			);
		}
		return $paxes;
	}
	
	private static function configDate($dob){
		$date  = explode('-',$dob);
		$month = RequestModifyPnr::parsingMonth($date[1]);
		$year  = RequestModifyPnr::parsingYear($date[0]);
		return $date[2].$month.$year;
	}
	
	private static function parsingMonth($monthNumber){
		$month = date("F", mktime(0, 0, 0, $monthNumber[1], 1));
		return strtoupper(substr($month, 0, 3));
	}
	
	private static function parsingYear($entireYear){
		return substr($entireYear,2,3);
	}
	
	private static function buildName($name){
		if(!preg_match('/\s/',$name)) {
			return strtoupper($name);
		}else{
			$names = explode(' ',$name);
			return strtoupper(implode('-',$names));
		}
	}
}