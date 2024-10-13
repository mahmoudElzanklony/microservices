<?php

namespace App\Patterns\factory\answers;

use App\Models\services;
use App\Patterns\strategy\answers\AuthorizeMember;
use App\Patterns\strategy\answers\AuthorizeOwner;

class AuthorizeUserServiceFactory
{
    public static function authorize(services $service_id):bool
    {
        if(auth()->user()->roleName() == 'owner'){
            $obj = new AuthorizeOwner();
            $status = $obj->check($service_id);
        }else if(auth()->user()->roleName()  == 'member'){
            $obj = new AuthorizeMember();
            $status = $obj->check($service_id);
        }else{
            $status = true;
        }
        return $status;
    }
}
