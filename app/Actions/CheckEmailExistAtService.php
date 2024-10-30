<?php

namespace App\Actions;

use App\Models\attributes;
use App\Services\Messages;

class CheckEmailExistAtService
{
    public static function check($data)
    {
        $data['attribute_id'] = collect($data['attribute_id'])->unique()->values()->all();
        $email_attr = attributes::query()->where('name','=','email')->first();
        if(!(in_array($email_attr->id,$data['attribute_id']))){
            abort(Messages::error(__('errors.email_must_exists')));
        }
    }
}
