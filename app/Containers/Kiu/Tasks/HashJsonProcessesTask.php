<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;
use App\Containers\Kiu\Models\KiuNotificationsModel;



class HashJsonProcessesTask extends Task
{
    public function run($text)
    {
        
        $space = " ";
		$contador = 0;
		$text = preg_split("/((\r?\n)|(\r\n?))/", $text);
        foreach($text as $key => $line){
            $line = explode(" ",preg_replace('/\s+/', ' ', $line));
            $oneway = false;
            $reverse = false;
            $date_start = '';
            if(
                (isset($line[16]) && $line[16] == '--oneway') ||
                (isset($line[17]) && $line[17] == '--oneway') ||
                (isset($line[18]) && $line[18] == '--oneway')
                ){
                $oneway = true;
            }
            if(
				(isset($line[16]) && $line[16] == '--reverse') || 
				(isset($line[17]) && $line[17] == '--reverse') || 
				(isset($line[18]) && $line[18] == '--reverse')){
                $reverse = true;
            }
            if(isset($line[16]) && $line[16] != '--oneway' && $line[16] != '--reverse'){
                $date_start = $line[16];
            }
            
			
            if (count($line) >= 16 && $date_start != 'kiu:fares') {
				$route = $line[13];
                $json['processes'][] = (object) array(
                    'proccess_id' => $line[1],
                    'route' => $route,
                    'command' => "php ". $line[11]. $space. $line[12]. $space. $line[13],
                    'footprint' => $line[14]."/".$line[15],
                    'date_start' => $date_start,
                    'reverse' => $reverse,
                    'oneway' => $oneway
                );
				$contador++;
            }
        }
		$json['counter'] = $contador;
        return (object) $json;
    }

}


