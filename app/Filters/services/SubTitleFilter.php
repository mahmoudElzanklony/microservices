<?php

namespace App\Filters\services;
use Closure;
class SubTitleFilter
{
    public function handle($request, Closure $next){
        if(request()->has('sub_title')){
            return $next($request)
                ->where('sub_title','LIKE','%'.request('sub_title').'%');
        }
        return $next($request);
    }
}
