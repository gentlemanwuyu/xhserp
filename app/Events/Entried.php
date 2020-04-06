<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class Entried extends Event
{
    use SerializesModels;

    /**
     * sku_entry表的ID
     *
     * @var integer
     */
    public $entry_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($entry_id)
    {
        $this->entry_id = $entry_id;
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
