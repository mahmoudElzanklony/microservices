<?php

namespace App\Actions;

use App\Models\User;

class SaveMemberAction
{
    public static function save($data , $type)
    {

        $user = User::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        if(!(array_key_exists('id',$data))){
            $user->assignRole($type);

            $user->createToken($data['email'])->plainTextToken;
        }

        return $user;
    }
}
