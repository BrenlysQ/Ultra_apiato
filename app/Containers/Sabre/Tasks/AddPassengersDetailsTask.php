<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Carbon\Carbon;
/**
 * Class RetrieveItineraryTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AddPassengersDetailsTask extends Task
{
    public function run($data)
    {
		//dd(json_decode($data));
        $request = '<PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_3" version="3.3.0" IgnoreOnError="false" HaltOnError="false">
                        <PostProcessing IgnoreAfter="false" RedisplayReservation="true" UnmaskCreditCard="true" />
                        <PreProcessing IgnoreBefore="true">
                        <UniqueID ID="" />
                        </PreProcessing>
                        <SpecialReqDetails>';
						foreach($data->paxes as $key => $pax){
							$dob = explode('-',$pax->dob);
							$dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
							$due = explode('-',$pax->due_date);
							$due = $due[2].'-'.$due[1].'-'.$due[0];
							$request .= '<SpecialServiceRQ>
								<SpecialServiceInfo>
									<AdvancePassenger SegmentNumber="A">
										<Document ExpirationDate="'.$due.'" Number="1234567890" Type="P">
										<IssueCountry>'.$pax->country_issue.'</IssueCountry>
										<NationalityCountry>'.$pax->cob.'</NationalityCountry>
										</Document>
										<PersonName DateOfBirth="'.$dob.'" Gender="'.$pax->gender.'" NameNumber="1.1" DocumentHolder="true">
											<GivenName>'.strtoupper($pax->firstname).'</GivenName>
											<MiddleName>RS</MiddleName>
											<Surname>'.strtoupper($pax->lastname).'</Surname>
										</PersonName>
										<VendorPrefs>
											<Airline Hosted="false" />
										</VendorPrefs>
									</AdvancePassenger>
								</SpecialServiceInfo>
							</SpecialServiceRQ>';
						}
						$request .=	'</SpecialReqDetails>';
					print_r($request); die;
        return $request;
    }
}
