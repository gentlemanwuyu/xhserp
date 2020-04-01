<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Deducted extends Event
{
    use SerializesModels;

    public $delivery_order_item_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($doi_ids)
    {
        $this->delivery_order_item_ids = $doi_ids;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
