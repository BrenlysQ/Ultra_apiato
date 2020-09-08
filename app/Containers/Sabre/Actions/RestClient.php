<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;


  class RestClient extends Action {
    public $token;
    private $tokenexp;
    public function __construct($token){
      $this->token = $token;
    }
    public function executeGetCall($path, $request = null) {
      $result = curl_exec($this->prepareCall('GET', $path, $request));
        //echo $result; die;
      return $result;
        //return json_decode($result);
    }
    public function executePostCall($path, $request) {
      $result = curl_exec($this->prepareCall('POST', $path, $request));
      return $result;
    }
    private function prepareCall($callType, $path, $request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $callType);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = $this->buildHeaders();
        array_push($headers, 'Content-Type: application/json');
        switch ($callType) {
        case 'GET':
            $url = getenv('SABRE_ENVIRONMENT').$path;
            if ($request != null) {
              $url .=  '?'.http_build_query($request);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_URL, getenv('SABRE_ENVIRONMENT').$path);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        return $ch;
    }
    public function GetToken(){
      $auth = new SabreAuth();
      $this->token = $auth->callForToken();
      //$_SESSION['token'] = $this->token;
      //$tokenexp = time() + $this->token->expires_in;
      //var_dump($this->token);
      return $this->token;
    }
    private function buildHeaders() {
        $headers = array(
            'Authorization: Bearer ' . $this->token,
            'Accept: */*'
        );
        return $headers;
    }
  }
 ?>
