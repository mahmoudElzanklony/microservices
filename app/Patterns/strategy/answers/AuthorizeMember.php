<?php

namespace App\Patterns\strategy\answers;

use App\Interfaces\answers\AuthorizeViewServiceInterface;
use App\Models\services;
use App\Models\services_privileges;
use App\Models\services_privileges_controls;

class AuthorizeMember implements AuthorizeViewServiceInterface
{

    public function check($service_id , $type = '')
    {
        $service = services_privileges::query()
            ->where('service_id','=',$service_id)
            ->where('user_id','=',auth()->id())
            ->when($type != '',function ($e) use ($type){
                $e->whereHas('controls',fn($q) =>
                    $q->whereHas('privilege',fn($x)=>
                        $x->where('name','=',$type)));
            })
            ->first();
        if($service){
            return true;
        }
        return false;

    }
}
