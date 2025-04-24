<?php

namespace App\Listeners;

use App\Events\ContactEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ContactCreateNotification;
use App\Notifications\ContactUpdateNotification;
use App\Notifications\ContactDeleteNotification;
use Illuminate\Queue\InteractsWithQueue;

class ContactListener {
    /**
    * Create the event listener.
    */

    public function __construct() {
        //
    }

    /**
    * Handle the event.
    */

    public function handle( ContactEvent $event ): void {
        $user = Auth::user();
        $contact = $event->contact;

        switch ( $event->action ) {
            case 'create':
            $user->notify( new ContactCreateNotification( $contact ) );
            break;
            case 'update':
            $user->notify( new ContactUpdateNotification( $contact ) );
            break;
            case 'delete':
            $user->notify( new ContactDeleteNotification( $contact ) );
            break;
        }
    }
}
