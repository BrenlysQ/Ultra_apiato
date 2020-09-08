<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\DB;


class SabreAuth extends Action{

    private function callForToken() {
      return $this->CallRestToken();
    }
    private function CallRestToken(){
      $ch = curl_init(getenv('SABRE_ENVIRONMENT') . "/v2/auth/token");
      $vars = "grant_type=client_credentials";
      $headers = array(
          'Authorization: Basic '.$this->buildCredentials(),
          'Accept: */*',
          'Content-Type: application/x-www-form-urlencoded'
      );
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_VERBOSE, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
      return json_decode($result);
    }
    private function CallSoapToken(){
      $token = SabreAuthSoap::GetSoapToken();
      return (object)$token;
    }
    public function ValidateToken($soap = false){
      $type = ($soap) ? 'SABRESOAP' : 'SABRE';
      $nowdate = date('Y-m-d H:i:s',time());
      $authres = DB::select('SELECT auth FROM external_auths WHERE type = "' . $type . '" AND expirein > ?',[$nowdate]);
      if (count($authres) > 0) {
        return $authres[0]->auth;
      }else{
        $token = $this->callForToken($soap);
        if($token && property_exists($token,'expires_in')){
          DB::delete('DELETE FROM external_auths WHERE type = "' . $type . '"');
          $expirein = date('Y-m-d H:i:s',($token->expires_in - 300) + time());
          DB::insert('INSERT INTO external_auths (type,auth,title,expirein) VALUES ("' . $type . '",?,"sabre",?)', [$token->access_token, $expirein]);
          return $token->access_token;
        }else{
          return false;
        }
      }
    }
    private function buildCredentials() {
        $credentials = getenv('SABRE_VERSION'). ":" . getenv('SABRE_USERID') . ":" . getenv('SABRE_GROUP') .":". getenv('SABRE_DOMAIN');
        $secret = base64_encode(getenv('SABRE_SECRET'));
        return base64_encode(base64_encode($credentials).":".$secret);
    }
}
