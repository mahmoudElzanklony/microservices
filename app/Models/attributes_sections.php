<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attributes_sections extends Model
{
    use HasFactory;

    protected $fillable = ['section_id','attribute_id'];
}
