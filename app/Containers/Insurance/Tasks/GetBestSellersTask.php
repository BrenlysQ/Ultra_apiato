<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Invoice\Models\ItemsModel; 
use App\Containers\Insurance\Models\VoucherModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;

class GetBestSellersTask extends Task
{
    public function run()
    {
        $features = FreelanceFeaturesModel::orderBy('completed_sales', 'desc')
                                            ->with('freelance')
                                            ->take(7)
											->get();
		$response = array();
		foreach ($features as $key => $featur){
			if($featur->id_freelance != 32){
				$response[$key] = array($featur->freelance->name.' '.$featur->freelance->lastname, $featur->completed_sales);
			}
		}
        return $response;
		//return $features;
    }
}
