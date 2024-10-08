<?php

namespace App\Actions;

use App\Models\privileges;
use App\Models\services_privileges;
use App\Models\services_privileges_controls;

class SavePrivilegesAction
{
    public static function save($user , $data)
    {
        services_privileges::query()->where('user_id','=',$user->id)->delete();
        foreach ($data as $key => $item){
            if(is_array($item)){
                $service_privilege = services_privileges::query()
                    ->create(['user_id'=>$user->id,'service_id'=>$key]);
                foreach ($item as $k => $priv) {
                    services_privileges_controls::query()->create([
                        'service_privilege_id'=>$service_privilege->id,
                        'privilege_id' => $priv
                    ]);
                }

            }
        }


    }
}
