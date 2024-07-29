<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients_services_sections_private_data extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','ip','latitude','longitude','info'];

    public function answers()
    {
        return $this->hasMany(clients_services_sections_data::class,'service_section_data_id');
    }
}
