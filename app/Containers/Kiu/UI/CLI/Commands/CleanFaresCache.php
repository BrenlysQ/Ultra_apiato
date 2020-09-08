<?php

namespace App\Containers\Kiu\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand;
use App\Containers\Kiu\Models\FaresCacheModel;
use App\Containers\Kiu\Models\FaresCacheHistory;
use Carbon\Carbon;
class CleanFaresCache extends ConsoleCommand
{

    protected $signature = 'clean:kiu_fares 
                            {cron : y or n if this command is execute from cronjob, rerquired} 
                            {limit? : Numeric value of the quantity of register to delete}';

    protected $description = 'Clean Kiu fares cache. 
                      examples:
                        php artisan clean:kiu_fares n
                        php artisan clean:kiu_fares s 
                        php artisan clean:kiu_fares s 3000 
                        php artisan clean:kiu_fares n 2000';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $cron = $this->argument('cron');
      ($this->argument('limit')) ? $limit = (int) $this->argument('limit') : $limit = 1000;
      $this->cleanCache($cron,$limit);
    }

    public function cleanCache($cron,$limit){
      if ($cron == 'y') {
        $this->cleaningCache($limit);
        dump('Se ha limpiado la Cache de Kiu');
      }else{
        do {
          $this->cleaningCache($limit);
          $answer = $this->ask('Desea Continuar? (y/n)');
        } while ( $answer == 'y');
        dump('\nSe ha limpiado la Cache de Kiu');
      }
    }

    public function cleaningCache($limit){
      $dt = Carbon::now();
      $dt->subDays(4);
      $info = FaresCacheModel::where('expirationdate','<',$dt)->limit($limit)->get();
      $this->deleteKiuCache($info);
    }

    public function deleteKiuCache($info){
      $bar = $this->output->createProgressBar(count($info));
      $bar->setFormat('very_verbose');
      foreach ($info as $key => $data) {
        $cache_backup = new FaresCacheHistory();
        $cache_backup->expirationdate = $data->expirationdate;
        $cache_backup->footprint = $data->footprint;
        $cache_backup->passengertype = $data->passengertype;
        $cache_backup->route = $data->route;
        $cache_backup->class = $data->class;
        $cache_backup->totalfare = $data->totalfare;
        $cache_backup->currency = $data->currency;
        $cache_backup->airpricinginfo = json_encode($data->airpricinginfo);
        $cache_backup->save();
        FaresCacheModel::findOrFail($data->id)->delete();
        $bar->advance();
      }
      $bar->finish();
    }
}
