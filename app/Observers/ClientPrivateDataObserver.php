<?php

namespace App\Observers;

use App\Models\clients_services_sections_private_data;
use App\Http\Traits\AdminTrait;
use App\Notifications\UserSubmitForm;
use App\Notifications\WalletChargingNotification;

class ClientPrivateDataObserver
{
    use AdminTrait;
    /**
     * Handle the User "created" event.
     */
    public function created(clients_services_sections_private_data $data): void
    {
        $service = $data->service;
        $service->user->notify(new UserSubmitForm($data));
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
