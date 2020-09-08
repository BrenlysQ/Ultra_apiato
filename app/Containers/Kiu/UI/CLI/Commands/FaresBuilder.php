<?php
namespace App\Containers\Kiu\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\Kiu\Tasks\AirAvailPayloadTask;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Commons\Logger;
use App\Containers\Kiu\Tasks\AirPriceTask;
use App\Containers\Kiu\Actions\CacheTransHandler;

class FaresBuilder extends ConsoleCommand
{

    protected $signature = 'kiu:fares {idtrans} {origin?} {destination?} {start?} {--oneway} {--direct} {--reverse}';

    protected $description = 'Get KIU fares';
    protected $spacer = '
';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
       ini_set('memory_limit', '128M');
	 $transaction = CacheTransHandler::Get($this->argument('idtrans'));
		//print_r($transaction); die;
        //Si la opcion --direct fue eescrita etnonces solo debemos buscar vuelos directos
        $direct = $this->option('direct');
		$oneway 	 = $this->option('oneway');
        if($direct){
          $direct = 'true';
        }else{
          $direct = 'false';
        }
        $start = time();
        if(!empty($this->argument('start'))){
          $outdate = Carbon::parse($this->argument('start'));
        }else{
          $outdate = Carbon::now()->addDay();
        }
    		if($transaction->currency == 4){
          $o2 = clone $outdate;
    			$maxdate = $o2->addMonths(4);
    		}else{
    			$maxdate = Carbon::now()->addDays(45);
    		}

		//dd($transaction);
        if(!$this->option('reverse')){
			$origin = $transaction->kiuroute->origin;
			$destination = $transaction->kiuroute->destination;
		}else{
			$origin = $transaction->kiuroute->destination;
			$destination = $transaction->kiuroute->origin;
		}
		$route = (object) array(
		  'origin' => $origin,
		  'destination' => $destination,
		  'currency' => $transaction->currency
		);

        CacheTransHandler::Start($transaction,$outdate,$maxdate);
        Logger::Write('Inicio busqueda de tarifas: ' . $route->origin . '/' . $route->destination);
        //echo 'Inicio: ' . date('d/m H:i',$start) . $this->spacer;
        //foreach ($routes as $key => $route) {

        $maxdays = $maxdate->diff($outdate)->days;
		if($oneway){
			$this->handle_oneway($maxdays,$outdate,$route,$direct);
		}else{
			$this->handle_roundtrip($maxdays,$outdate,$route,$direct);
		}
        //For para construir 30 dias de cache
        $end = time();
        CacheTransHandler::End($transaction);
        Logger::Write($route->origin . '/' . $route->destination .' - Inicio: ' . date('H:i',$start) . ', final: ' . date('H:i',$end) . ', Elapsed time: ' . ($end - $start));
    }
	private function handle_oneway($maxdays,$outdate,$route,$direct){
		for($i = 0; $i <= $maxdays; $i++){
          $payload = (object) array(
				"departure_city" => $route->origin,
				"destination_city" => $route->destination,
				"departure_date" => $outdate,
				"passenger_type" => 'ADT',
				"direct" => $direct,
				"currency" => $route->currency,
				'cabin' => '',
				'adult_count' => 1,
      			);
            //Obtengo el payload XML que le enviaremos al webservice
            Logger::Write($route->origin . '/' . $route->destination .'/' . $route->origin .
                ', del ' . $outdate . ' OW');
            //Availabilyty XML
            $request = (new AirAvailPayloadTask())->run($payload);
            /*dd($request);*/
            //Availabilyty RESPONSE KIU
			//print_r($request); die;
            $response = (new MakeRequestTask())->run($request);
			//print_r($response); //die;
            /*dd($response);*/
            //Si la respuesta es 1000 quiere decir que falta la ida
            //Por lo que el ciclo salta al siguiente dia
			$pricer = (new AirPriceTask())->run($response, $payload, true, true);

          $outdate->addDay();
        }
	}
	private function handle_roundtrip($maxdays,$outdate,$route,$direct){
		for($i = 0; $i <= $maxdays; $i++){
          $retdate = clone $outdate;
          $retdate->addDays(2);
          //For para construir payload por Ruta por dia con hasta 15 de separacion
          for($j = 0; $j < ($maxdays - $i - 2); $j++){
            $payload = (object) array(
				"departure_city" => $route->origin,
				"destination_city" => $route->destination,
				"departure_date" => $outdate,
				"return_date" => $retdate,
				"passenger_type" => 'ADT',
				"direct" => $direct,
				"currency" => $route->currency,
				'cabin' => '',
				'adult_count' => 1,
      			);
            //Obtengo el payload XML que le enviaremos al webservice
            Logger::Write($route->origin . '/' . $route->destination .'/' . $route->origin .
                ', del ' . $outdate . ' al ' . $retdate);
            //Availabilyty XML
            $request = (new AirAvailPayloadTask())->run($payload);
            /*dd($request);*/
			//echo $request; die;
            //Availabilyty RESPONSE KIU
			//print_r($request); die;
            $response = (new MakeRequestTask())->run($request);
			print_r($response); //die;
            /*dd($response);*/
            //Si la respuesta es 1000 quiere decir que falta la ida
            //Por lo que el ciclo salta al siguiente dia
			$pricer = (new AirPriceTask())->run($response, $payload);
			if($pricer == 1000){
			  break;
			}
            // die;
            //$response = json_decode(file_get_contents(storage_path('response.txt')));


            //Log::debug($this->spacer.$response.$this->spacer);

            $retdate->addDay();
          }
          $outdate->addDay();
        }

	}
}
