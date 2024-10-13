<?php

namespace App\Patterns\strategy\answers;

use App\Interfaces\answers\AuthorizeViewServiceInterface;
use App\Models\services;
use App\Models\services_privileges;

class AuthorizeMember implements AuthorizeViewServiceInterface
{

    public function check($service_id)
    {
        $service = services_privileges::query()
            ->where('service_id','=',$service_id)
            ->where('user_id','=',auth()->id())->first();
        if($service){
            return true;
        }
        return false;

    }
}
