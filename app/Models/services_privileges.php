<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services_privileges extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','service_id'];

    public function service()
    {
        return $this->belongsTo(services::class,'service_id');
    }

    public function controls()
    {
        return $this->hasMany(services_privileges_controls::class,'service_privilege_id');
    }

    public function privileges()
    {
        return $this->hasManyThrough(
            Privileges::class,           // The final model (target)
            services_privileges_controls::class, // The intermediate model (pivot)
            'service_privilege_id',     // Foreign key on the intermediate model
            'id',                       // Foreign key on the final model
            'id',                       // Local key on the starting model
            'privilege_id'              // Local key on the intermediate model
        );
    }
}
