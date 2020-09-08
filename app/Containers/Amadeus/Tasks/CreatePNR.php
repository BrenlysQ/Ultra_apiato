<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
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
use Amadeus\Client\RequestOptions\Pnr\Element\ManualCommission;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;  
use App\Commons\TagsGdsHandler;
use App\Commons\CommonActions;
use App\Containers\UltraApi\Actions\UltraBook;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Mail\BookErrorMail;
class CreatePNR extends Task { 
	public function run($airSegments,$client,$cache){
		$opt = new PnrCreatePnrOptions(); 
		$opt->actionCode = 10; //0
		$paxesdetails = json_decode(Input::get('datapaxes'));
		/*Esto esta en el PaxesPNRTask*/
		if($cache->itinerary->inf_count == 0){
			foreach ($paxesdetails as $key => $pax) {
				if($pax->type == 'ADT'){
					$opt->travellers[$key] = CreatePNR::buildAdult($key,$pax);
				}elseif($pax->type == 'CHD'){
					$opt->travellers[$key] = CreatePNR::buildChild($key,$pax);
				}
			}	  
		}else{
			$infant = array();
			foreach ($paxesdetails as $key => $pax) {
				if($pax->type == 'INF'){
					$infant[] = CreatePNR::buildInfant($key,$pax);
				}
			}
			$inf_counter = 0;
			foreach ($paxesdetails as $key => $pax) {
				if(count($infant) > $inf_counter){
					if($pax->type == 'ADT'){
						$opt->travellers[$key] = CreatePNR::buildAdult($key,$pax,$infant,$inf_counter);
						$inf_counter = $inf_counter + 1;
					}
				}elseif($pax->type == 'ADT'){
					$opt->travellers[$key] = CreatePNR::buildAdult($key,$pax);
				}elseif($pax->type == 'CHD'){
					$opt->travellers[$key] = CreatePNR::buildChild($key,$pax);
				}
			}
		}
		$contactpax = json_decode(Input::get('contactpax'));
		$date = CreatePNR::parsingDate(Carbon::now()->addDay(1));
		$opt->elements[] = new Ticketing([
            'ticketMode' => Ticketing::TICKETMODE_CANCEL,
            'date' => \DateTime::createFromFormat('Y-m-d',$date, new \DateTimeZone('UTC'))
        ]);
		$opt->elements[] = new Contact([
		    'type' => Contact::TYPE_PHONE_MOBILE,
		    'value' => $contactpax[0]->phone 
		]);
		$opt->elements[] = new Contact([
		    'type' => Contact::TYPE_EMAIL,
		    'value' => $contactpax[0]->email 
		]);
		$opt->elements[] = new ManualCommission([
            'passengerType' => ManualCommission::PAXTYPE_PASSENGER,
            'indicator' => 'FM',
            'percentage' => 0
        ]);
		$createdPnr = $client->pnrCreatePnr($opt);
		// if(Input::get('log'))
			CommonActions::clientInteraction('pnrCreatePnr',$client);
		if ($createdPnr->status === Result::STATUS_OK) {
			sleep(3);
			$pnrContent = $client->pnrRetrieve(
				new PnrRetrieveOptions(['recordLocator' => $createdPnr->response->pnrHeader->reservationInfo->reservation->controlNumber])
			);
			CommonActions::clientInteraction('pnrRetrieve',$client);
			return CreatePNR::workflow($cache,$client);
		}else{
			$response = CommonActions::CreateObject();
			$response->status = 'Ha Ocurrido un Error (PNR_AddMultiElements)';
			$response->messages = 'Error al momento de crear el PNR';
			$response->result = $createdPnr->response;
			return json_encode($response);
		}
	}
	
	private static function workflow($cache,$client){
		$createdPnr = (new WorkflowBookTask())->run($cache,$client);
		if ($createdPnr->status === Result::STATUS_OK) {
			$pnrReply = $client->pnrAddMultiElements(
				new PnrAddMultiElementsOptions([
					'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE //ET: END
				])
			);
			// if(Input::get('log'))
				CommonActions::clientInteraction('pnrSavePnr',$client);
			if($pnrReply->status === Result::STATUS_OK){
				$response = CreatePNR::book(
					$pnrReply,
					$client,
					$cache
				);
				return $response;
			}else{
				return array(
					"amadeus"  => true, 
					"status"   => $pnrReply->status,
					"messages" => $pnrReply->messages,
					"result"   => $pnrReply->result
				);
					/*Error de Cambios simultaneos en el PNR
					Se ignoran los antiguos cambios*/
					/*if($pnrReply->messages[0]->code == '8111'){
						$pnrReplyIgnore = $client->pnrAddMultiElements(
							new PnrAddMultiElementsOptions([
								'actionCode' => 21
							])
						);
					}
					// if(Input::get('log'))
						CommonActions::clientInteraction('ERR Ignoring Changes pnr',$client);
					 /*Se ejecuta nuevamene la funcion de forma recursiva
					 para realizar nuevamente el workflow*/
					//return CreatePNR::workflow($cache,$client);
			}
		}else{
			$itinerary = $cache->itinerary;
			//Mail::to('plusultradesarrollo@gmail.com')->send(new BookErrorMail($itinerary));
			return array(
				"amadeus"  => true, 
				"status"   => $createdPnr->status,
				"messages" => $createdPnr->messages,
				"result"   => $createdPnr->result
			);
		}
	}
	
	private static function parsingDate($date){
		$date = Carbon::parse($date)->toDateString();
		return $date;
	}
	
	private static function book($pnr,$client,$cache){
		$logoutResponse = $client->securitySignOut();
		$odo = $cache->flight;
		$odo->outbound = $odo->outbound;  
		$odo->return = $odo->return;
		$itinerary = json_decode((TagsGdsHandler::GetGdsTag())->itinerary);
		$data = CommonActions::CreateObject();
		$data->itineraryid = $pnr->response->pnrHeader->reservationInfo->reservation->controlNumber;
		$data->odo = $odo;
		$data->itinerary = $itinerary;
		$data->freelance = Input::get('freelance_id',0);
		return json_encode(UltraBook::Book($data));
	}
	
	private static function buildAdult($key,$pax,$infant = false,$inf_counter = false){
		if($infant){
			return new Traveller([
				'number' => $key + 1,
				'firstName' => $pax->firstname,
				'lastName' => $pax->lastname,
				'travellerType' => $pax->type,
				'infant' => $infant[$inf_counter]
			]);
		}else{
			return new Traveller([
				'number' => $key + 1,
				'firstName' => $pax->firstname,
				'lastName' => $pax->lastname,
				'travellerType' =>  Traveller::TRAV_TYPE_ADULT,
				'withInfant' => false
			]);
		}
	}
	private static function buildChild($key,$pax){
		return new Traveller([
			'number' => $key + 1,
			'firstName' => $pax->firstname,
			'lastName' => $pax->lastname,
			'travellerType' =>  Traveller::TRAV_TYPE_CHILD,
			'withInfant' => false
		]);
	}
	
	private static function buildInfant($key,$pax){
		return new Traveller([
			'firstName' => $pax->firstname,
			'lastName' => $pax->lastname,
			'travellerType' => $pax->type,
		]);
	}
}