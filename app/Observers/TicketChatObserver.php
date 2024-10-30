<?php

namespace App\Observers;

use App\Models\clients_services_sections_private_data;
use App\Models\clients_services_tickets_chat;
use App\Http\Traits\AdminTrait;
use App\Notifications\TicketChatNotification;

class TicketChatObserver
{
    use AdminTrait;
    /**
     * Handle the User "created" event.
     */
    public function created(clients_services_tickets_chat $data): void
    {
        $answer = clients_services_sections_private_data::query()
            ->where('id','=',$data->service_private_answer_id)
            ->with('last_chat_message','service.user')
            ->when($data->type != 'client', function ($query) {
                return $query->with('owner');
            })
            ->firstOrFailWithCustomError('not found ticket data for this service');
        if($data->type == 'client'){
            // send to owner and members that has access to this service answer
            $answer->service->user->notify(new TicketChatNotification($answer));
        }else{
            // send to client
            $answer->owner->notify(new TicketChatNotification($answer));
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('wallet')) {
            // The "wallet" column has been changed
            $originalStatus = $user->getOriginal('wallet');
            $user->notify(new WalletChargingNotification($user,$originalStatus));
        }

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
