<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\OrderCreated' => [
            'App\Listeners\OrderCreatedListener',
        ],
        'App\Events\OrderPaid' => [
            'App\Listeners\OrderPaidListener',
        ],
        'App\Events\OrderShipped' => [
            'App\Listeners\OrderShippedListener',
        ],
        'App\Events\OrderConfirmed' => [
            'App\Listeners\OrderConfirmedListener',
        ],
        'App\Events\OrderCanceled' => [
            'App\Listeners\OrderCanceledListener',
        ],
        'App\Events\DeliveryConfirmed' => [
            'App\Listeners\DeliveryConfirmedListener',
        ],
        'App\Events\GoodsModelDeleting' => [
            'App\Listeners\GoodsModelDeletingListener',
        ],
        'App\Events\GoodsModelDeleted' => [
            'App\Listeners\GoodsModelDeletedListener',
        ],
        'App\Events\GoodsCategoryDeleting' => [
            'App\Listeners\GoodsCategoryDeletingListener',
        ],
        'App\Events\BrandDeleting' => [
            'App\Listeners\BrandDeletingListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
