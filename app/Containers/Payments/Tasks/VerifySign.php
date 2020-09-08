<?php

namespace App\Containers\Payments\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
use App\Containers\SocialAuth\Actions\SocialLoginAction;
class VerifySign extends Task
{

    public function run($referer, $sign)
    {
      //$sat = SatelliteHandler::GetByDomain($referer);
      $sat = CommonActions::CreateObject();
      $sat->secret_key = 'EJRLU4a0y2Jbq7NDCfQ1MViaMppqeqT3AdPMlOJIY6w=';
      $sat->domain = 'http://experienc.com';
      $newEncrypter = new \Illuminate\Encryption\Encrypter( base64_decode($sat->secret_key), config( 'app.cipher' ) );
      $decrypted = json_decode($newEncrypter->decrypt( $sign ));
      if($decrypted->secret == $sat->secret_key){
        return (new SocialLoginAction())->run($decrypted,$sat->domain);
      }else{
        return false;
      }
    }

}
