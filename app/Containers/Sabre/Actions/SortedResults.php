<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Commons\CommonActions;
class SortedResults extends Action{
  public $flights;
  public $tagpu;
  private $indexouts;
  private $indexrets;
  private $outs;
  private $rets;
  private $flightscount;
  private $payload;
  public function __construct($itineraries,$data){
    $start_date = new DateTime();
    $this->tagpu = $data->tagpu;
    $this->payload = $data->payload;
    Log::debug('Creating object');
    $this->flights = array();
    $this->indexouts = array();
    $this->indexrets = array();
    $this->outs = array();
    $this->rets = array();
    $this->flights = array();
    $this->flightscount = 0;
    $i=0;
    //print_r($itineraries); die;
    foreach ($itineraries as $itn => $itinerary) {
      $odo = new TravelOption($itinerary, $this->payload);
      if(!CommonActions::isRoundTrip($this->payload->trip)){
        $this->flights[$itn]['outbound'][] = $odo->outboundflight;
        $this->flights[$itn]['price'] = $odo->price;
      }else{
        if($itn == 0){
          $this->NewOdo($odo, 0);
        }else{
          $key = $odo->outboundflight->GetIfootPrint();
          if(!isset($this->indexouts[$key])){
            $this->NewOdo($odo, $this->flightscount);
          }else{
            $key = $this->indexouts[$key];
            $footp = $odo->outboundflight->GetFootprint();
            if(!in_array($footp, $this->outs)){
              $this->flights[$key]['outbound'][] = $odo->outboundflight;
              $this->outs[] = $footp;
            }
          }
          $key = $odo->returnflight->GetIfootPrint();
          if(!isset($this->indexrets[$key])){
            $this->NewOdo($odo, $this->flightscount);
          }else{
            $key = $this->indexrets[$key];
            $footp = $odo->returnflight->GetFootprint();
            //print_r($this->rets) ; echo $footp; die;
            if(!in_array($footp, $this->rets)){
              $this->flights[$key]['return'][] = $odo->returnflight;
              $this->rets[] = $footp;
            }
          }
        }
      }
    }
    $since_start = $start_date->diff(new DateTime());
    Log::debug('End Creating, Elapsed: ' . $since_start->s .' segs.');
  }
  private function NewOdo($odo, $key){
    //dd($odo);
    $this->flights[$key]['outbound'][] = $odo->outboundflight;
    $keyo = $odo->outboundflight->GetIfootPrint();
    $this->indexouts[$keyo] = $key;
    $this->outs[] = $odo->outboundflight->GetFootprint();
    if(CommonActions::isRoundTrip($this->payload->trip)){
      $this->flights[$key]['return'][] = $odo->returnflight;
      $keyo = $odo->returnflight->GetIfootPrint();
      $this->indexrets[$keyo] = $key;
      $this->rets[] = $odo->returnflight->GetFootprint();
    }
    $this->flights[$key]['price'] = $odo->price;
    $this->flightscount++;
  }
}?>
