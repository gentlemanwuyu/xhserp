<?php
/**
 * 出货完成事件
 *
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/2/13
 * Time: 21:11
 */

namespace App\Events;

class EgressFinished extends Event
{
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