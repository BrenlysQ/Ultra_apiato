<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
/**
 * Class PriceBuilderTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class PriceBuilderTask extends Task
{

    public function run($charges, $currency)
    {
		//print_r($charges); die;
		$base = $charges->VehicleCharge->Amount;
		$total = $charges->VehicleCharge->TotalCharge->Amount;
		$totalfee = $total * 0.10;
		$totaltax = 0;
		$currency = CurrenciesHandler::GetByCode($currency);
		$returno = CommonActions::CreateObject();
		$returno->GlobalFare = CommonActions::CreateObject();
		$returno->GlobalFare->FeeAmount = $totalfee;
		$returno->GlobalFare->BaseInter = $base;  //Base como viene de Sabre
		$returno->GlobalFare->Base2Show = $base + $totalfee; //Fee Plusultra + Base
		$returno->GlobalFare->TotalTax = $totaltax; //Impuestos, 0 hasta saber cuales son
		$returno->GlobalFare->BaseAmount = $total + $totalfee;
		$returno->GlobalFare->TotalAmount = $total + $totalfee;
		$returno->GlobalFare->CurrencyCode = $currency->code_visible;
		return $returno;
    }
}
