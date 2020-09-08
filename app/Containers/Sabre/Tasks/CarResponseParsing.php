<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class CarsResponseParsing extends Task
{
    public $price;
    public $capacity;
    public $example;
    public $numBags;
    public $numDoors;
    public $type;
	public $vendor;
	public $rph;

    public function run($option, $price, $vendor, $rph)
    {
      if(!property_exists($option, 'NumBags')){
        $option->NumBags = 'N/I';
      }
	  //print_r($price); die;
	  //print_r($option); die;
      $this->capacity  = $option->Capacity;
      $this->example   = $option->Example;
      $this->numBags   = $option->NumBags;
      $this->numDoors  = $option->NumDoors;
      $this->type      = $option->Type;
	  $this->vendor    = $vendor;
	  $this->rph	   = $rph;
      $this->price	   = $price;
	  //print_r($this); die;
      return $this;
    }
}
