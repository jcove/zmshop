<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessException;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $ip                         =   $request->getClientIp();
//        $client                     =   new Client();
//        $response                   =   $client->request('GET', 'http://ip.taobao.com/service/getIpInfo.php', [
//            'query' => ['ip' => $ip]
//        ]);
//        $data                       =   json_decode($response->getBody(),true);
//
//        if($data && $country=$data['data']['country']){
//            if(strpos(config('shop.access_barred_country'),$country)!==false){
//                throw new AccessException(trans('message.access_control'));
//            }
//        }

        return $next($request);
    }

}
