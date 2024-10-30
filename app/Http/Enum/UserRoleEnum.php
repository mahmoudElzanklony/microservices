<?php

namespace App\Http\Enum;

enum UserRoleEnum:string
{
    case admin = 'admin';
    case member = 'member';
    case owner = 'owner';
    case client = 'client';
}
