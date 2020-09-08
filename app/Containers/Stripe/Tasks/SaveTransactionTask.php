<?php

namespace App\Containers\Stripe\Tasks;

use Illuminate\Http\Request;
use App\Ship\Parents\Tasks\Task;

use Exception;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Config;
use App\Containers\Stripe\Models\StripeModel;

/**
 * Class ChargeWithStripeTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SaveTransactionTask extends Task
{

    public function run(Request $request, $response)
    {
        
       //dd($response);
       $stripePayment = new StripeModel();
       $stripePayment->charge_id = $response['id'];
       $stripePayment->status = $response['status'];
       $stripePayment->amount = $response['amount'];
       $stripePayment->auth_token = '';
       $stripePayment->response = json_encode($response);
       $stripePayment->date = Carbon::createFromTimestamp($response['created'])->toDateTimeString();
       $stripePayment->card_type = $response['source']['brand'];
       $stripePayment->email = $request->email;
       $stripePayment->save();
       //dd($stripePayment);

       return $stripePayment;
    }
}       
