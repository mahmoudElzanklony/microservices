<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','main_title','sub_title','type'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function sec_attr_data()
    {
        return $this->belongsToMany(attributes::class,services_sections_data::class,
            'service_id','attribute_id');
    }
}
