<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\HilbeToday\Models\DollarPrice;
use App\Containers\HilbeToday\Models\HtConfig;
use App\Mail\DollarPriceNotification;
use Mail;

class StorePriceTask extends Task
{
    public function run($req, $price_object) {

    $dollar = new DollarPrice();
    $dollar->dt_price = $price_object->USD->dolartoday;
    $dollar->timestamp = $price_object->_timestamp->fecha;
    $dollar->hilbe_price = $req->price_usd;
    $dollar->price_bsf = $req->price_bsf;
    $dollar->ht_config_percentage_bsf = $req->percentage_bsf;
    $dollar->percentage = $req->percentage_usd;
    $dollar->save();
    $config = HtConfig::latest()->first();
    $emails = $config->emails;
    $emails = json_decode($emails,true);
    $data = (object) array(
        'dollar' => $dollar,
        'emails' => $emails
    );
    Mail::to(getenv('MAIL_TO_CEO'))->send(new DollarPriceNotification($data));

    return $data;
    }
}
