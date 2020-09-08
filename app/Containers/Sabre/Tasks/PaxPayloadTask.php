<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Commons\SabreCommons;
/**
 * Class PaxPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class PaxPayloadTask extends Task
{
    public function run()
    {
      $payload = CommonActions::CreateObject();
      $paxesdetails = json_decode(Input::get('datapaxes'));
      $numberparty = 0;
      $countnames = 0;
	    $payload->freelance = (Input::get('freelance_id'));
      $payload->cinfopax = '';
      $payload->secf = '';
      $payload->advpax = '';
      $payload->contact_numbers = '';
      $payload->emails = '';
      $payload->linkprice = '';
      foreach($paxesdetails as $index => $pax){
        $countnames++;
        if(!isset($pricingtypes[$pax->type])){ //Si el tipo de PAX no existe en el array lo agrego con 1 como CONTADOR
          $pricingtypes[$pax->type] = 1;
        }else{
          $pricingtypes[$pax->type]++; //SI YA EXSITE AUMENTO EL CONTADOR
        }

        $paxkey = array_search($pax->type,array_keys($pricingtypes)) + 1; //Obtengo la posicion del tipo del pax en el array y le sumo 1
        $namen = $countnames . '.1';
        if($pax->type != 'INF'){
          $inf = 'false';
          $numberparty++;
          if($pax->type == 'ADT'){
            $payload->contact_numbers .= '
            <ContactNumber NameNumber="' . $namen . '" Phone="' . $pax->phone . '" PhoneUseType="H"/>';
            $payload->emails .= '
            <Email Address="' . $pax->email . '" NameNumber="' . $namen . '" ShortText="ABC123" Type="TO"/>';
          }
        }else{
          $inf = 'true';

        }
        $due_date = CommonActions::FormatDate($pax->due_date); //Formateo la fecha de expedicion a mysqlformat
        //$dob = CommonActions::FormatDate($pax->due_date);

        $payload->advpax .= '
        <AdvancePassenger SegmentNumber="A">
          <Document ExpirationDate="' . $due_date . '" Number="' . $pax->passport . '" Type="' . $pax->document . '">
            <IssueCountry>' . $pax->country_issue . '</IssueCountry>
            <NationalityCountry>' . $pax->cob . '</NationalityCountry>
          </Document>
          <PersonName DateOfBirth="' . $pax->dob . '" DocumentHolder="true" Gender="' . $pax->gender . '" NameNumber="' . $namen . '" >
              <GivenName>' . $pax->firstname . '</GivenName>
              <Surname>' . $pax->lastname . '</Surname>
          </PersonName>
        </AdvancePassenger>';
        $payload->secf .= '
        <SecureFlight SegmentNumber="A" >
          <PersonName DateOfBirth="' . $pax->dob . '" Gender="' . $pax->gender . '" NameNumber="' . $namen . '">
            <GivenName>' . $pax->firstname . '</GivenName>
            <Surname>' . $pax->lastname . '</Surname>
          </PersonName>
        </SecureFlight>';
        $payload->cinfopax .= '
        <PersonName Infant="' . $inf . '" NameNumber="' . $namen . '" PassengerType="' . $pax->type . '">
            <GivenName>' . $pax->firstname . '</GivenName>
            <Surname>' . $pax->lastname . '</Surname>
        </PersonName>';
        $payload->linkprice .= '<Link NameNumber="' . $namen . '" Record="' . count($pricingtypes) . '" />';

        //$tot += $pax['qty'];
        //$pricingtypes .= '<PassengerType Code="' . $pax['type'] . '" Quantity="2" />';
      }
      $payload->numberinparty = $numberparty;
      $payload->pritypes = '';
      $imax = 0;
      foreach($pricingtypes as $type => $qty){
        $imax++;
        if($imax == count($pricingtypes)){
          $payload->pritypes .= '<PassengerType Code="' . $type . '" Force="true" Quantity="' . $qty . '" />';
        }else{
          $payload->pritypes .= '<PassengerType Code="' . $type . '" Quantity="' . $qty . '" />';
        }

      }
      return $payload;
    }
}
