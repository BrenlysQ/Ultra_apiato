<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\Itineraries\Tasks\VerifyKiuItineries;
use Illuminate\Support\Facades\Input;
use App\Containers\Instagram\Models\InstaContactsModel;
use App\Containers\Satellite\Models\API_satellite;
use App\Containers\Invoice\Models\RefundsModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use Mail;
use App\Mail\CanceledLocalizableAlertMail;
use App\Mail\CanceledPaidInvoiceAlertMail;
use App\Containers\Itineraries\Tasks\DecreaseAirlineBalanceTask;
use App\Containers\Itineraries\Tasks\CreateAirlineBalanceHistoryTask;


class LocalizableCheckerTask extends Task
{

  public function run()
  {
    set_time_limit(0);
    $itineraries = ItinModel::where('status',null)->get();
    $contacts = InstaContactsModel::where('contactable', 1)->get();
    foreach ($itineraries as $itinerary) {
      if ($itinerary->itinerary->se == 2) {
        $request = (new VerifyKiuItineries)->run($itinerary);
        $response = (new MakeRequestTask())->run($request);
        if ($response->TravelItinerary->ItineraryInfo->Ticketing->TicketingStatus == 3) {
          $itinerary->status = 2;
          $itinerary->update();
          $airline = (new DecreaseAirlineBalanceTask)->run($response->amount,$response->currency);
          $history = (new CreateAirlineBalanceHistoryTask)->run($response);
        }elseif ($response->TravelItinerary->ItineraryInfo->Ticketing->TicketingStatus == 5) {
          $itinerary->load(['item' => function($q) {
            $q->with('invoice');
          }]);
          $itin = json_decode($itinerary);
          foreach ($contacts as $contact) {
            Mail::to($contact->email)->send(new CanceledLocalizableAlertMail($itin));
          }
          Mail::to($itin->item->invoice->contact_pax->email)->send(new CanceledLocalizableAlertMail($itin));
          Mail::to($itin->item->invoice->usersatdata->email)->send(new CanceledLocalizableAlertMail($itin));
          $invoice = InvoiceModel::where('id', $refund->invoice_id)->first();
          $invoice->total_amount = $invoice->total_amount - $refund->price;
          $invoice->administration_status = 3;
          $invoice->update();
          if ($invoice->total_amount <= $invoice->total_paid) {
            foreach ($contacts as $contact) {
              Mail::to($contact->email)->send(new CanceledPaidInvoiceAlertMail($itin));
            }
            $refund = new RefundsModel();
            $refund->invoice_id = $itin->item->invoice->id;
            $refund->itinerary_id = $itin->id;
            $refund->price = $itin->odo->price->GlobalFare->TotalAmount;
            $refund->balance = abs($invoice->total_paid - $invoice->total_amount);
            $refund->save();
          }
          $itinerary->status = 3;
          $itinerary->update();
          dd('hola');
        }
      }
    }
    return $resp = array('status' => 'sent' );

  }
}
