<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Database\Events\QueryExecuted' => [
            'App\Listeners\QueryListener',
        ],
        'App\Events\EgressFinished' => [
            'App\Listeners\EgressFinishedOrderListener',
            'App\Listeners\EgressFinishedReturnOrderListener',
        ],
        'App\Events\Deducted' => [
            'App\Listeners\DeductedOrderListener',
        ],
        'App\Events\Entried' => [
            'App\Listeners\EntriedPurchaseOrderListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
