<?php

namespace App\Containers\Payments\Middlewares;

use App\Ship\Parents\Middlewares\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Containers\Satellite\Actions\SatelliteHandler;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Auth;
use App\Containers\Payments\Tasks\VerifySign;

class RequestsPaymentMiddleware extends Middleware
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return  mixed
     */
    public function handle(Request $request, Closure $next)
    {
      //Auth::logout();
      //dd(Auth::user());
      //$user = Auth::loginUsingId(2);
      //dd(session());
      $sign = $request->input('sign');
      if(!Auth::user() && empty($sign)){
        // $referer = $request->server('HTTP_REFERER');
        // $urlback = url('/usite/callback');
        // //echo $urlback; die;
        // //$sat = SatelliteHandler::GetByDomain($referer);
        // $sat = CommonActions::CreateObject();
        // $sat->client_id = 3;
        // $sat->domain = 'http://www.experienciasplus.com.ve/';
        // $query = http_build_query([
        //     'client_id' => $sat->client_id,
        //     'redirect_uri' => $urlback,
        //     'response_type' => 'code',
        //     'scope' => '',
        // ]);
        // dd("HELLO API");
        return redirect('/login');
      }elseif(!empty($sign) && !Auth::user()){
        $user = (new VerifySign())->run($request->server('HTTP_REFERER'),$sign);
        return $next($request);
      }else{
        return $next($request);
      }
      return $next($request);

        //return redirect($sat->domain . 'oauth/authorize?' . $query);
        //return redirect('http://www.experienciasplus.com.ve/');
    }
}
