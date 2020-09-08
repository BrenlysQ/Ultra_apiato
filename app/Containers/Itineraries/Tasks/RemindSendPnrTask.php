<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use Carbon\Carbon;
use Mail;
use App\Mail\RemindSendPnrMail;


class RemindSendPnrTask extends Task
{
    public function run()
    {
      $itineraries = ItinModel::get();
      foreach ($itineraries as $itinerarie) {
        $itinerarie->load(['item' => function($q) {
                           $q->with('invoice');
                       }]);
        $created = Carbon::parse($itinerarie->created_at);
        if (isset($itinerarie->invoice->total_paid)) {
          if ($itinerarie->invoice->total_paid == $itinerarie->invoice->total_amount && (Carbon::now()->diffInDays($created) == 30 || Carbon::now()->diffInDays($created) <= 15)) {
            Mail::to($itinerarie->usersatdata->email)->send(new RemindSendPnrMail($itinerarie));
          }
        }elseif ($itinerarie->paxes == null && (Carbon::now()->diffInDays($created) == 30 || Carbon::now()->diffInDays($created) <= 15)) {
          Mail::to($itinerarie->usersatdata->email)->send(new RemindSendPnrMail($itinerarie));
        }
      }
      return $itineraries;
    }

}
