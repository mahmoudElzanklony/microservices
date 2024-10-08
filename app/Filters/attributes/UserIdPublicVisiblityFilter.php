<?php

namespace App\Filters\attributes;
use Closure;
class UserIdPublicVisiblityFilter
{
    public function handle($request, Closure $next){
        if(request()->filled('user_id_attr') && request()->filled('visibility_attr')){
            return $next($request)
                ->whereRaw('visibilitys = "'.request('visibility_attr').'"');
        }
        return $next($request);
    }
}
