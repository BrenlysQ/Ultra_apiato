<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Process\Process;



class ListProcessesTask extends Task
{
    public function run()
    {
      $space = " ";
	     $proc = new Process('ps aux | grep artisan');
      //$proc = new Process('ls');
      $proc->setWorkingDirectory(base_path());
      $proc->mustRun();
	    $text = $proc->getOutput();

  		$count = count(preg_split("/((\r?\n)|(\r\n?))/", $text));
  		$contador = 0;
  		///print_r($text); die;
      foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $key => $line){
        $line = explode(" ",$line);
    		if(
    			!isset($line[25]) ||
    			(isset($line[37]) && ($line[37] == 'artisan')) ||
    			(isset($line[30]) && $line[30] == 'instagram:directs') ||
    			(isset($line[34]) && $line[34] == 'artisan')
    		){
    			continue;
    		}
    		$oneway = false;
    		$reverse = false;
    		$date_start = '';
    		if(
    			(isset($line[31]) && $line[31] == '--oneway') ||
    			(isset($line[30]) && $line[30] == '--oneway') ||
    			(isset($line[32]) && $line[32] == '--oneway')
    			){
    			$oneway = true;
    		}
    		if((isset($line[31]) && $line[31] == '--reverse') || (isset($line[30]) && $line[30] == '--reverse')){
    			$reverse = true;
    		}
    		if(isset($line[30]) && $line[30] != '--oneway' && $line[30] != '--reverse'){
    			$date_start = $line[30];
    		}
  		//print_r($line);
          $route = explode("/",$line[25]);
          if (count($line)>28) {
            $json['processes'][] = array(
  			         'proccess_id' => $line[5],
                 'route' => $line[27],
                 'command' => "php ".$line[26].$space.$line[27],
                 'footprint' => $line[28]."/".$line[29],
                 'date_start' => $date_start,
  			          'reverse' => $reverse,
  			          'oneway' => $oneway
            );
  		  $contador++;
          }
          /*if (array_key_exists(32,$line)) {
            $json['processes'][$key]['oneway'] = 'true';
          }elseif(!(array_key_exists(32,$line)) && count($line)>28) {
            $json['processes'][$key]['oneway'] = 'false';
          }*/
        }
  	  $json['count'] = $contador;
        $json = json_encode($json);

        /*try {
            $proc->mustRun();
            echo $proc->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }*/
        return $json;
    }

}
