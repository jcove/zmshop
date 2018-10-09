<?php

namespace App\Http\Middleware;

use Closure;

class FilterImage
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
        $filters                            =   ['image','images','cover','avatar','value','code','icon'];
        foreach ($filters as $filter){
            if($request->has($filter)){
                $input                      =   $request->get($filter);
                if(is_array($input)){
                    foreach ($input as $key => $value){
                        $input[$key]       =   original_path($value);
                    }

                }else{
                    $input                  =   original_path($input);
                }

                $request->offsetSet($filter,$input);
            }
        }
        return $next($request);
    }
}
