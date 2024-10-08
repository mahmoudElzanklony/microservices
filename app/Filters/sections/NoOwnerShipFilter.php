<?php

namespace App\Filters\sections;
use Closure;
class NoOwnerShipFilter
{
    public function handle($request, Closure $next){
        if(!request()->has('ownership') && auth()->guard('sanctum')->check()){
            return $next($request)
                ->where('user_id','=',auth()->guard('sanctum')->id());
        }
        if(auth()->guard('sanctum')->check()){
            return $next($request)->whereRaw('user_id = '.(auth()->guard('sanctum')->id() ?? 0).' OR visibility = "public"');
        }else{
            return $next($request);
        }
    }
}
