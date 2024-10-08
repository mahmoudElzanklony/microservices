<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services_privileges_controls extends Model
{
    use HasFactory;

    protected $fillable = ['service_privilege_id','privilege_id'];

    public function privilege()
    {
        return $this->belongsTo(privileges::class,'privilege_id');
    }
}
