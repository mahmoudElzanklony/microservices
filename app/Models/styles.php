<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class styles extends Model
{
    use HasFactory;
    protected $fillable = ['styleable_id','styleable_type','name'];

    public function styleable()
    {
        return $this->morphTo();
    }

}
