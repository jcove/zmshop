<?php

namespace App\Listeners;

use App\Events\OrderCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCanceledListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderCanceled  $event
     * @return void
     */
    public function handle(OrderCanceled $event)
    {
        //
    }
}
