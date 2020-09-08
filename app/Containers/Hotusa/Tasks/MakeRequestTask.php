<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Ixudra\Curl\Facades\Curl;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Storage;


class MakeRequestTask extends Task
{
    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($request, $json = true) {
      set_time_limit(0);
	  $json_data = json_decode(CommonActions::XML2JSON($request));
	  //print_r(json_decode($json_data)); die;
	  $header_log = '

	  Servicio ' . $json_data->tipo . ' REQUEST

';

$header_log2 = '

	  Servicio ' . $json_data->tipo . ' RESPONSE

';
		$file = 'scenario8';
	  //Storage::disk('cachekiu')->append($file, $header_log . $request);
      $data = array(
                      "codigousu" => getenv('CODIGO_USU'),
                      "clausu" => getenv('CLAU_USU'),
                      "afiliacio" => getenv('AFILIACION_HOT'),
                      "secacc" => getenv('SEC_ACC'),
					  "Accept-Encoding" => 'gzip',
                      "xml" => $request
                    );
      $response = Curl::to(getenv('HOT_ENVIRONMENT'))
        ->withHeaders($this->headers())
        ->withData($data)
        ->withTimeout(0)
        ->post();
	//Storage::disk('cachekiu')->append($file, $header_log2 . $response);
      if($json) {
        $response = CommonActions::XML2JSON($response);
        return json_decode($response);
      } else {
        return $response;
      }
    }

  public static function headers(){
    return array(
      'User-Agent: PHP XMLRPC 1.1',
      'Host: xml.hotelresb2b.com',
      'Content-Type: application/x-www-form-urlencoded'
    );
  }

}
