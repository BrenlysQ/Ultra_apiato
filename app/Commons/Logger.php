<?php
namespace App\Commons;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Containers\Kiu\Models\KiuNotificationsModel;
class Logger{
  private static $spacer = '
';
  public static function Write($mess, $screen = true, $file = 'kiulog'){
    $mess = date('d/m H:i') . ' - ' . $mess;
	$notification = new KiuNotificationsModel();
	$notification->footprint = hash('md5',getmypid()); 
	$notification->data = $mess;
	$notification->save();
	
    /*Storage::disk('cachekiu')->append($file, $mess);
    if($screen){
      echo $mess . self::$spacer;
    }*/
	
  }
}
