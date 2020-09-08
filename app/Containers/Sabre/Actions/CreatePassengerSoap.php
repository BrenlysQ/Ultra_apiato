<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
use App\Commons\TagsGdsHandler;
use SoapClient;
use DateTime;
use DateInterval;
use App\Containers\Sabre\Commons\SabreCommons;

use Illuminate\Support\Facades\Input;
class CreatePassengerSoap extends Action{
  public static function CreatePassenger(){
    return CreatePassengerSoap::Request();
  }
  private static function ValidatePayload(){
    $legs = json_decode(Input::get('legs'));
    $cache = SabreCommons::IntineraryCache($legs);
    if(property_exists($cache,'error')){
      return false;
    }else{
      //echo Input::get('datapaxes'); die;
      $paxesdetails = json_decode(Input::get('datapaxes'));
      $numberparty = 0;
      $countnames = 0;
      $cinfopax = '';
      $ssrpax = '';
      $linkprice = '';
      foreach($paxesdetails as $index => $pax){
        $countnames++;
        if(!isset($pricingtypes[$pax->type])){
          $pricingtypes[$pax->type] = 0;
        }
        if($pax->type != 'INF'){
          $inf = 'false';
          $numberparty++;
        }else{
          $inf = 'true';
        }
        if($index == 0){
          $ssrpax .= '
                      <AdvancePassenger SegmentNumber="A">
                        <Document ExpirationDate="2018-05-26" Number="' . $pax->passport . '" Type="P">
                          <IssueCountry>VE</IssueCountry>
                          <NationalityCountry>VE</NationalityCountry>
                        </Document>
                        <PersonName DateOfBirth="1980-12-02" DocumentHolder="true" Gender="M" NameNumber="' . $countnames . '.1" >
                            <GivenName>' . $pax->firstname . '</GivenName>
                            <Surname>' . $pax->lastname . '</Surname>
                        </PersonName>
                      </AdvancePassenger>';
        }else{
          $ssrpax .= '<SecureFlight SegmentNumber="A" >
                      <PersonName DateOfBirth="1990-12-21" Gender="M" NameNumber="' . $countnames . '.1">
                        <GivenName>' . $pax->firstname . '</GivenName>
                        <Surname>' . $pax->lastname . '</Surname>
                      </PersonName>
                    </SecureFlight>';
        }
        $cinfopax .= '<PersonName Infant="' . $inf . '" NameNumber="' . $countnames . '.1" PassengerType="' . $pax->type . '">
                          <GivenName>' . $pax->firstname . '</GivenName>
                          <Surname>' . $pax->lastname . '</Surname>
                      </PersonName>';
        $linkprice .= '<Link NameNumber="' . $countnames . '.1" Record="' . count($pricingtypes) . '" />';
        $pricingtypes[$pax->type]++;

        //$tot += $pax['qty'];
        //$pricingtypes .= '<PassengerType Code="' . $pax['type'] . '" Quantity="2" />';
      }
      $pritypes = '';
      foreach($pricingtypes as $type => $qty){
        $pritypes .= '<PassengerType Code="' . $type . '" Quantity="' . $qty . '" />';
      }
      /*if($tot <> count($paxesdetails)){
        return false;
      }*/
      $ticketlimit = new DateTime();
      $ticketlimit->add(new DateInterval('P2D'));
      return (object) array(
        'odo' => new TravelOption($cache->itinerary, $cache->itinstored),
        'itinerary' => $cache->itinerary,
        'currency' => $cache->currency,
        'itinstored' => $cache->itinstored,
        'pricingtypes' => $pritypes,
        'numberinparty' => $numberparty,
        'cinfopax' => $cinfopax,
        'ssrpax' => $ssrpax,
        'linkprice' => $linkprice,
        'datapaxes' => $paxesdetails,
        'ticketing_limit' => str_replace(' ','T',$ticketlimit->format('m-d H:00'))
      );
    }
  }
  private static function ParseResponse($response, $payload){
    /*return (object) array(
      'itinerary' => $payload->itinstored,
      'odo' => $payload->odo,
      'itineraryid' => str_random(6)
    );*/
    //$response = file_get_contents('/opt/lampp/htdocs/ultra-api/PassengerDetailsRS.xml');
    $response = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response);
    $xml = simplexml_load_string($response);
    $ns = $xml->getNamespaces(true);
    //echo $xml->soap-env;
    $ItineraryRef = $xml->Body->PassengerDetailsRS->ItineraryRef["ID"];
    $id = $ItineraryRef[0];
    if(!empty($id)){
      return (object) array(
        'itinerary' => $payload->itinstored,
        'odo' => $payload->odo,
        'itineraryid' => (string) $id
      );
    }else{
      return (object) array(
        'error' => true,
        'message' => 'ItineraryRef unavailable.'
      );
    }
  }
  private static function Request(){
    //$token
    if(!$payload = CreatePassengerSoap::ValidatePayload()){
      return (object) array(
        'error' => true,
        'message' => 'Invalid payload or tagid'
      );
    }
	
	//dd($payload->itinstored->trip);
    $cache = $payload->itinerary;
    $currency = $payload->currency;
    //var_dump($currency); die;
    $count = 1;
    $segsel = '';
    $govcarrier = '';
    $segmentsdata = '';
    for($i=0; $i<$payload->itinstored->trip; $i++){
      $first = $count;
      //$xmlstr .= '<OriginDestinationInformation>';
      $segments = $cache->AirItinerary->OriginDestinationOptions->OriginDestinationOption[$i]->FlightSegment;
      foreach ($segments as $key => $segment) {
        $segmentsdata .= '
        <FlightSegment DepartureDateTime="' . $segment->DepartureDateTime .'"  ArrivalDateTime="' . $segment->ArrivalDateTime . '"'
          . ' FlightNumber="' . $segment->FlightNumber . '" NumberInParty="' . $payload->numberinparty . '" ResBookDesigCode="' . $segment->ResBookDesigCode . '" Status="NN">
          <DestinationLocation LocationCode="' . $segment->ArrivalAirport->LocationCode . '" />
          <MarketingAirline Code="' . $segment->MarketingAirline->Code . '" FlightNumber="' . $segment->FlightNumber . '" />
          <OriginLocation LocationCode="' . $segment->DepartureAirport->LocationCode . '" />
        </FlightSegment>';
        //$segsel .= '<SegmentSelect Number="' . $count . '" RPH="' . $count . '"/>
                    //';
        $govcarrier .= '<GoverningCarrierOverride RPH="' . $count . '">
                          <Airline Code="' . $segment->MarketingAirline->Code . '"/>
                        </GoverningCarrierOverride>';
        $count++;
      }
      $segsel .= '<SegmentSelect EndNumber="' . ($count - 1) . '" Number="' . $first . '" RPH="' . ($i + 1) . '"/>';
      //$xmlstr .= "</OriginDestinationInformation>";
    }
    $enhancedrq = '<EnhancedAirBookRQ xmlns="http://services.sabre.com/sp/eab/v3_7" version="3.7.0" HaltOnError="false">
                    <OTA_AirBookRQ>
                      <RetryRebook Option="true"/>
                      <HaltOnStatus Code="UC"/>
                      <HaltOnStatus Code="LL"/>
                      <HaltOnStatus Code="UN"/>
                      <HaltOnStatus Code="NO"/>
                      <HaltOnStatus Code="HL"/>
                      <OriginDestinationInformation>
                        ' . $segmentsdata . '
                      </OriginDestinationInformation>
                      <RedisplayReservation NumAttempts="5" WaitInterval="2000"/>
                    </OTA_AirBookRQ>
                    <OTA_AirPriceRQ>
                      <PriceRequestInformation>
                        <OptionalQualifiers>
                          <PricingQualifiers>
                            ' . $payload->pricingtypes . '
                          </PricingQualifiers>
                        </OptionalQualifiers>
                      </PriceRequestInformation>
                    </OTA_AirPriceRQ>
                    <PostProcessing IgnoreAfter="false">
                      <RedisplayReservation WaitInterval="500"/>
                    </PostProcessing>
                    <PreProcessing IgnoreBefore="false"/>
                  </EnhancedAirBookRQ>';
    $passengerrq = '<PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_3" version="3.3.0">
                      <PostProcessing IgnoreAfter="false" RedisplayReservation="true">
                          <EndTransactionRQ>
                              <EndTransaction Ind="true" />
                              <Source ReceivedFrom="PLUSULTRA TESTING" />
                          </EndTransactionRQ>
                      </PostProcessing>
                      <PriceQuoteInfo>
                          ' . $payload->linkprice . '
                      </PriceQuoteInfo>
                      <SpecialReqDetails>
                          <AddRemarkRQ>
                              <RemarkInfo>
                                  <FOP_Remark Type="CASH" />
                                  <Remark Type="General">
                                      <Text>TEST GENERAL REMARK</Text>
                                  </Remark>
                              </RemarkInfo>
                          </AddRemarkRQ>
                          <SpecialServiceRQ>
                              <SpecialServiceInfo>
                                  ' . $payload->ssrpax . '
                              </SpecialServiceInfo>
                          </SpecialServiceRQ>
                      </SpecialReqDetails>
                      <TravelItineraryAddInfoRQ>
                          <AgencyInfo>
                              <Address>
                                  <AddressLine>DIRECCION</AddressLine>
                                  <CityName>CARACAS</CityName>
                                  <CountryCode>VE</CountryCode>
                                  <PostalCode>1071</PostalCode>
                                  <StateCountyProv StateCode="MI" />
                                  <StreetNmbr>OFIC 18</StreetNmbr>
                                  <VendorPrefs>
                                      <Airline Hosted="true" />
                                  </VendorPrefs>
                              </Address>
                              <Ticketing PseudoCityCode="4Q0H" ShortText="TEST" TicketTimeLimit="' . $payload->ticketing_limit . '" TicketType="7TAW"/>
                          </AgencyInfo>
                          <CustomerInfo>
                              <ContactNumbers>
                                  <ContactNumber LocationCode="KTM" NameNumber="1.1" Phone="817-555-1212" PhoneUseType="H" />
                              </ContactNumbers>
                              <Email Address="churromorales20@gmail.com" NameNumber="1.1" />
                              ' . $payload->cinfopax . '
                          </CustomerInfo>
                      </TravelItineraryAddInfoRQ>
                  </PassengerDetailsRQ>';
    //echo $enhancedrq; die;
    $token = SabreAuthSoap::GetSoapToken();
    $token = $token['access_token'];
    $soapclient = new SACSSoapClient($token);
    $soapclient->setLastInFlow(false);
    $response = $soapclient->doCall($enhancedrq,'EnhancedAirBookRQ');
    $response = $soapclient->doCall($passengerrq,'PassengerDetailsRQ');
    return CreatePassengerSoap::ParseResponse($response, $payload);
    // return "hello Api";
    //return $response;
  }
}
?>
