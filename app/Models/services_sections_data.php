<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services_sections_data extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','section_id','attribute_id','type'];

    public function section()
    {
        return $this->belongsTo(sections::class,'section_id');
    }
    public function attribute()
    {
        return $this->belongsTo(attributes::class,'attribute_id');
    }
    public function service()
    {
        return $this->belongsTo(services::class,'service_id');
    }
}
