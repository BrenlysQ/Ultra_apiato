<?php

namespace App\Containers\Stripe\Actions;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\App;
use App\Ship\Parents\Actions\Action;
use App\Containers\Stripe\Tasks\ChargeCustomerTask;
use App\Containers\Stripe\Tasks\SaveTransactionTask;
use App\Containers\Stripe\Tasks\CreateStripeTokenTask;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\Payments\Gateways\CreditCard\CreditCardHandler;

/**
 * Class CreateStripeAccountAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class ChargeStripeAction extends Action
{

    public function manageRequest(Request $request){
        //dd('manageRequest',$request->type);
        $stripe = new Stripe(getenv('STRIPE_SECRET'),getenv('STRIPE_VERSION'));
        if($request->type != 'true'){
            $payment = CreditCardHandler::AddPayment($request);
            if(!$payment){
              return false;
            }else{
                $stripe_payment = ChargeStripeAction::makeCharge($request, $stripe, $request->token);
                $saved = (new SaveTransactionTask())->run($request, $stripe_payment);
                //dd($saved);
                $payment->st = 2;
                InvoicesHandler::MkPayment($payment);
                $payment->save();
                $saved->payment()->save($payment);
                $payment->load(
                    'processor',
                    'currency')
                    ->load(['invoice' => function($q) {
                        $q->with(['items' => function($q) {
                            $q->with('invoiceable');
                        }]);
                    }])
                    ->load('pgatewaydata');
                    $payment->load(['satellite_token' => function($q){
                    $q->with('token');
                }]);

                //dd(json_decode($payment));

                $response = (object) array(
                    'stripe_payment' => $stripe_payment,
                    'payment' => $payment,
                );
                //print_r(json_decode($payment)); die;
                //$response = (new SatelliteEmailCurlTask())->run($payment->satellite_token,$payment,'creditpayment');

                return json_encode($response);
            }
        }else{
            //dd($request, 'Hasta cuando');
            $stripe_payment = ChargeStripeAction::makeCharge($request, $stripe, $request->token);
            
            $saved = (new SaveTransactionTask())->run($request, $stripe_payment);
            
            $response = (object) array(
                'stripe_payment' => $stripe_payment,
            );
            
            return json_encode($response);
        }
            
    }

    public function makeCharge(Request $request,Stripe $stripe, $token){
        $charge = (new ChargeCustomerTask($stripe))->run($request, $token);

        return $charge;
    }
}