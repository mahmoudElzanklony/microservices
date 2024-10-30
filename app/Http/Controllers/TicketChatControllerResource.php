<?php

namespace App\Http\Controllers;

use App\Http\Requests\ticketChatFormRequest;
use App\Http\Resources\TicketChatResource;
use App\Http\Resources\UserResource;
use App\Models\clients_services_owners;
use App\Models\clients_services_sections_private_data;
use App\Models\clients_services_tickets_chat;
use App\Services\Messages;
use Illuminate\Http\Request;

class TicketChatControllerResource extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = clients_services_sections_private_data::query()
            ->where('id','=',request('id'))
            ->with('owner.user','chat','service')
            ->firstOrFailWithCustomError('not found ticket data for this service');

        return TicketChatResource::make($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ticketChatFormRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['type'] = auth()->user()->roleName();
        $result = clients_services_tickets_chat::query()->create($data);
        //return Messages::success(__('messages.saved_successfully'));
    }

    public function end_chat()
    {
        $obj = clients_services_owners::query()
            ->where('service_private_answer_id','=',request('id'))
            ->first();
        if($obj->status == 0){
            return Messages::error(__('errors.ticket_ended_before'));
        }else{
            $obj->status = 0;
            $obj->save();
            return Messages::success(__('messages.ticket_ended_successfully'));
        }
    }

}
