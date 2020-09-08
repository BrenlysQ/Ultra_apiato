<?php namespace sketchpad\controllers;

use davestewart\sketchpad\config\SketchpadConfig;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use App\Containers\Satellite\Models\API_satellite;


/**
 * An example controller, just to get you started
 *
 * @label start here
 */
class ExampleController
{

    public function index (SketchpadConfig $config)
    {
        return view('sketchpad::example/index', compact('config'));
    }

    /**
     * An example method, just to get you started...
     */
    public function welcome ($name = 'World')
    {
/*        $GLOBALS['Codusu'] = 'D85709'; 
      $GLOBALS['Secacc'] = '117596'; 
      $GLOBALS['Codigousu'] = 'HIMJ'; 
      $GLOBALS['Clausu'] = 'xml480247'; 
      $GLOBALS['Afil'] = 'RS';

      //XML Request 
      $GLOBALS['header_Conn'] = "codigousu=".$Codigousu."&clausu=".$Clausu."&afiliacio=".$Afil."&secacc=".$Secacc."&xml=";*/
      
             //XML Request
  $xml = "codigousu=" . 'HIMJ';
  $xml .= "&clausu=" . 'xml480247';
  $xml .= "&afiliacio=" . 'RS';
  $xml .= "&secacc=" . '117596';
  $xml .= "&xml=";
  $xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
  $xml2 .= "<peticion>\n";
  $xml2 .= "<tipo>7</tipo>\n";
  $xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
  $xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
  $xml2 .= "<parametros>\n";
  //$xml2 .= "\t<pais>ES</pais>\n";
  $xml2 .= "\t<pais>ESBCN</pais>\n";
  $xml2 .= "\t<radio>9</radio>\n";
  $xml2 .= "\t<idioma>1</idioma>\n";
  $xml2 .= "\t<afiliacion>RS</afiliacion>\n";
  $xml2 .= "\t<usuario>D85709</usuario>\n";
  $xml2 .= "\t<marca></marca>\n";
  $xml2 .= "</parametros>\n";
 /*$xml2 .=  "<nombre>Petición de Provincias</nombre>
            <agencia>Agencia de Prueba</agencia>
            <tipo>6</tipo>";*/
/* $xml2 .=  "<tipo>110</tipo>
<nombre>Disponibilidad Varias Habitaciones Regímenes</nombre>
<agencia>Agencia de Prueba</agencia>
<parametros>
<hotel></hotel>
<pais>MV</pais>
<provincia>MVMOL</provincia>
<poblacion></poblacion>
<categoria>0</categoria>
<radio>9</radio>
<fechaentrada>08/07/2017</fechaentrada>
<fechasalida>08/09/2017</fechasalida>
<marca></marca>
<afiliacion>RS</afiliacion>
<usuario>D85709</usuario>
<numhab1>1</numhab1>
<paxes1>2-1</paxes1>
<edades1>8</edades1>
<numhab2>0</numhab2>
<paxes2>0</paxes2>
<numhab3>0</numhab3>
<paxes3>0</paxes3>
<idioma>1</idioma>
<duplicidad>1</duplicidad>
<comprimido>2</comprimido>
<informacion_hotel>0</informacion_hotel>
</parametros>";*/
  $xml2 .= "</peticion>";
    //print_r($xml);
  $xml .= urlencode($xml2);
  $length = strlen($xml);
  //XML Connection
  $fp = @fsockopen("xml.hotelresb2b.com", 80);
  fputs($fp, "POST http://xml.hotelresb2b.com/xml/listen_xml.jsp HTTP/1.0\nUser-Agent: PHP XMLRPC
  1.1\r\n");
  fputs($fp, "Host: xml.hotelresb2b.com\n");
  fputs($fp, "Content-Type: application/x-www-form-urlencoded\n");
  fputs($fp, "Content-Length: " . $length . "\n");
  fputs($fp, "\n");
  fputs($fp, $xml);

  $respuesta = "";
  while(!feof($fp)) $respuesta .= fgets($fp);
  fclose ($fp);
 print_r($respuesta);
  //XML Answer
 //$xmlstr = substr($respuesta, strpos($respuesta, "<?xml"));
 // print_r($xmlstr);
   /*$xmlAnswer = new \SimpleXMLElement($xmlstr); //Simple XML is available from php5
  $no_hotels = count($xmlAnswer->parametros->hoteles->hotel);
  for($i=0;$i<$no_hotels;$i++) {
    $hotel = $xmlAnswer->parametros->hoteles->hotel[$i];
    print($hotel->nombre_h); // prints te hotelname etc.
  }*/
        
    }

}
