<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\FalseItineraryModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class CreateFalseItineraryTask extends Task
{

  public function run($items)
  { 
	
    foreach ($items as $key => $item){
      $invoice = new InvoiceModel();
      $invoice = $self->common->CreateObject();
      $invoice->to_pay = $item->to_pay;
      $invoice->base = $item->base;
      $invoice->fee_pu = $item->fee_pu;
      $invoice->customer = $item->customer;
      $invoice->fee_sat = $item->fee_sat;
      $invoice->tot_com = $item->tot_com;
      $invoice->internal_base = $item->internal_base;
      $invoice->tax = $item->tax;
      $invoice->base_wtc = $item->base_wtc;
      $invoice->total = $item->total;
      $invoice->yce = $item->yce;
      $invoice->total_paid = $item->total_paid;
      $invoice->items = [];
      $invoice->items[] = $item;
      $invoice->save();
      
      $itinerary = new FalseItineraryModel();
      $itinerary->base = $item->base;
      $itinerary->fee_pu = $item->fee_pu;
      $itinerary->fee_sat = $item->fee_sat;
      $itinerary->loc = '';
      $itinerary->tax = $item->tax;
      $itinerary->tkt = '';
      $itinerary->total = $item->total;
      $itinerary->st = 1;
      $itinerary->save();    
    }    
  }
}