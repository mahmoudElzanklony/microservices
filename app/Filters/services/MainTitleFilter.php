<?php

namespace App\Filters\services;
use Closure;
class MainTitleFilter
{
    public function handle($request, Closure $next){
        if(request()->has('main_title')){
            return $next($request)
                ->where('main_title','LIKE','%'.request('main_title').'%');
        }
        return $next($request);
    }
}
