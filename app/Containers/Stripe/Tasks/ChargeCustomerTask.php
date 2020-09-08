<?php

namespace App\Containers\Stripe\Tasks;

use App\Containers\Stripe\Exceptions\StripeAccountNotFoundException;
use App\Containers\Stripe\Exceptions\StripeApiErrorException;
use App\Containers\User\Models\User;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Http\Request;

use Exception;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Config;

/**
 * Class ChargeWithStripeTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class ChargeCustomerTask extends Task
{

    public $stripe;

    /**
     * StripeApi constructor.
     *
     * @param \Cartalyst\Stripe\Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe->make(Config::get('services.stripe.secret'), Config::get('services.stripe.version'));
    }

    /**
     * @param \App\Containers\User\Models\User $user
     * @param                                  $amount
     * @param string                           $currency
     *
     * @return  array|null
     */
    public function run(Request $request, $token)
    {

        $response = $this->stripe->charges()->create([
            'source' => $token,
            'currency' => 'USD',
            'amount'   => $request->totpay,
            'description' => $request->description,
            'statement_descriptor' => "Plus Ultra, C.A"
        ]);

        
        return $response;
    }

}       
