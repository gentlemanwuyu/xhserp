<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/8
 * Time: 14:13
 */

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class Egressed extends Event
{
    use SerializesModels;

    /**
     * 出货单ID
     *
     * @var array
     */
    public $delivery_order_id;

    public function __construct($delivery_order_id)
    {
        $this->delivery_order_id = $delivery_order_id;
    }
}