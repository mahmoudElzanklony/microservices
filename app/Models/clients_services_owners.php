<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients_services_owners extends Model
{
    use HasFactory;

    protected $fillable = ['service_private_answer_id','user_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
