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

    public function service()
    {
        return $this->belongsTo(services::class,'service_id');
    }

    public function client_service_owner()
    {
        return $this->hasOne(clients_services_owners::class,'service_private_answer_id');
    }

    public function owner()
    {
        return $this->hasOne(clients_services_owners::class,'service_private_answer_id');
        /*return $this->hasOneThrough(User::class,
            clients_services_owners::class,
            'service_private_answer_id', // Foreign key on the intermediate table (clients_services_owners)
            'id', // Foreign key on the final table (User)
            'id', // Local key on the current model (clients_services_sections_private_data)
            'user_id' );// Local key on the intermediate model (clients_services_owners)*/
    }

    public function chat()
    {
        return $this->hasMany(clients_services_tickets_chat::class,'service_private_answer_id');
    }
    public function last_chat_message()
    {
        return $this->hasOne(clients_services_tickets_chat::class,'service_private_answer_id')
            ->orderByDesc('id');
    }
}
