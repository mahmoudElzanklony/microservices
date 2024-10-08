<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients_services_sections_data extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id','service_section_data_id','answer','answer_type'];

    public function attribute()
    {
        return $this->belongsTo(attributes::class,'attribute_id');
    }
}
