<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients_services_tickets_chat extends Model
{
    use HasFactory;

    protected $fillable = ['service_private_answer_id','user_id','content','type'];
}
