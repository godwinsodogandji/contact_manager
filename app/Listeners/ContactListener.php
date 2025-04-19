<?php

namespace App\Listeners;

use App\Events\ContactEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ContactCreateNotification;
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
        $user->notify( new ContactCreateNotification( $contact ) );

    }
}
