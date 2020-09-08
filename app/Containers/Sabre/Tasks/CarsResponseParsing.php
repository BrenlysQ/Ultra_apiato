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
    public $basePrice;
    public $fee;
    public $taxes;
    public $totalAmount;
    public $capacity;
    public $example;
    public $numBags;
    public $numDoors;
    public $type;

    public function run($option, $price)
    {
      if(!property_exists($option, 'NumBags')){
        $option->NumBags = 'No Disponible';
      }
      $this->basePrice    = $price->TotalCharge->Amount;
      $this->capacity     = $option->Capacity;
      $this->example      = $option->Example;
      $this->numBags      = $option->NumBags;
      $this->numDoors     = $option->NumDoors;
      $this->type         = $option->Type;
      $this->fee          = $this->basePrice * 0.09;
      $this->taxes        = $this->basePrice * 0.12;
      $this->totalAmount  = $this->basePrice + $this->fee + $this->taxes;

      return $this;
    }
}
