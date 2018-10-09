<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/13
 * Time: 17:10
 */

namespace App\Payments;


use App\Models\Order;

interface PaymentInterface
{
    function getPayUrl(Order $order);
    function getPayCode();

}