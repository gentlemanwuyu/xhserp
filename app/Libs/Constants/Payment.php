<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/25
 * Time: 11:49
 */

class Payment
{
    const CASH          = 1; // 现金
    const REMITTANCE    = 2; // 汇款
    const CHECK         = 3; //支票/汇票
}

class PaymentMethod
{
    const CASH      = 1; // 现金
    const CREDIT    = 2; // 货到付款
    const MONTHLY   = 3; // 月结
}