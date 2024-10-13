<?php

namespace App\Patterns\strategy\answers;

use App\Interfaces\answers\AuthorizeViewServiceInterface;
use App\Models\services;

class AuthorizeOwner implements AuthorizeViewServiceInterface
{

    public function check($service_id)
    {
        $service = services::query()
            ->where('id','=',$service_id)
            ->where('user_id','=',auth()->id())->first();
        if($service){
            return true;
        }
        return false;
    }
}
