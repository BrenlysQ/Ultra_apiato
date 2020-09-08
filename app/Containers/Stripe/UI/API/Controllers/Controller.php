<?php

namespace App\Containers\Stripe\UI\API\Controllers;

use App\Containers\Stripe\Actions\CreateStripeAccountAction;
use App\Containers\Stripe\Actions\ChargeStripeAction;
use App\Containers\Stripe\UI\API\Requests\CreateStripeAccountRequest;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Stripe\Tasks\ChargeStripeCustomerTask;
use Illuminate\Http\Request;


/**
 * Class Controller.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class StripeController extends ApiController
{
    public function chargeCustomer(Request $request)
    {
        //dd('Controller', $request);
        $response = (new ChargeStripeAction())->manageRequest($request);
        //print_r($response); die;
        
        return $response;
    }

}