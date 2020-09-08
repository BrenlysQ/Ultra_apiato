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
class CarsOptionCleanerTask extends Task
{

    public function run($group)
    {
    //dd($group);
    $cars = $group->options;
    foreach ($cars as $index => $car) {
      if (strpos($car->example, ' OR') !== false) {
        $car->example = explode(' OR', $car->example)[0];
      }
      foreach($cars as $comparisonIndex => $comparisonCar){
        if($index !== $comparisonIndex){
          if($car->example === $comparisonCar->example){
            if($car->capacity === $comparisonCar->capacity){
              if($car->numBags === $comparisonCar->numBags){
                if($car->numBags === $comparisonCar->numBags){
                  unset($cars[$comparisonIndex]);
                }
              }
            }
          }
        }
      }
    }
      return $this;
    }
}

