<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Http\Controllers\WebhookController;

class StripeEventListener extends WebhookController
{

    /**
    * Handle the event.
    *
    * @param  object  $event
    * @return void
    */
   public function handle(WebhookReceived $event)
   {
       if ($event->payload['type'] === 'invoice.payment_succeeded') {
           if($user = $this->getUserByStripeId($event->payload['data']['object']['customer'])) {
               Log::info('The payment succeeed');
               // queue and event to record purchase transaction in purchase table
               // notify user of charge
               // notify admin of charge
           }
       }

       if ($event->payload['type'] === 'invoice.payment_failed') {
           Log::info('The payment failed');
           // queue an event that creates a prompt in the dashboard to update payment method
           // notify user to update payment method
           // notify admin of failed payment attempt
       }
   }
}
