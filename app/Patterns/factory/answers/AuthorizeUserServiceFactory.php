<?php

namespace App\Patterns\factory\answers;

use App\Models\services;
use App\Patterns\strategy\answers\AuthorizeMember;
use App\Patterns\strategy\answers\AuthorizeOwner;

class AuthorizeUserServiceFactory
{
    public static function authorize($service_id , $type = ''):bool
    {
        if(auth()->user()->roleName() == 'owner'){
            $obj = new AuthorizeOwner();
            $status = $obj->check($service_id);
        }else if(auth()->user()->roleName()  == 'member'){
            $obj = new AuthorizeMember();
            $status = $obj->check($service_id,$type);
        }else{
            $status = true;
        }
        return $status;
    }
}
