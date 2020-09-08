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
class CreateStripeTokenTask extends Task
{

    public $stripe;
    public $token;

    /**
     * StripeApi constructor.
     *
     * @param \Cartalyst\Stripe\Stripe $stripe
     */
    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe;
    }

    
    public function run(Request $request)
    {
        $token = $this->stripe->tokens()->create(array(
            'card' => array(
                'number' => $request->card,
                'exp_month' => $request->exp_month, 
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc
            )
        ));

        return $token;
    }

}       
