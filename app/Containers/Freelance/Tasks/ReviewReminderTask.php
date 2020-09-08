<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Mail\ReviewReminderMail;
use Mail;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceReviewsModel;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;

use Carbon\Carbon;



class ReviewReminderTask extends Task
{
    public function run()
    {
      $freelances = FreelanceModel::all();
      $invoices = InvoiceModel::whereColumn('total_amount', 'total_paid')->get();
      foreach ($invoices as $invoice) {
        foreach ($freelances as $freelance) {
          if ($invoice->satelite == $freelance->id_satellite ) {
            $review = FreelanceReviewsModel::where('id_invoice', $invoice->id)->first();
            if ($review == null) {
              $invoice->freelance = $freelance;
              Mail::to($freelance)->send(new ReviewReminderMail($invoice));
            }
          }
        }
      }
	    return $invoices;
    }
}
