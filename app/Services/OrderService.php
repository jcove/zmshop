<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/18
 * Time: 10:05
 */

namespace App\Services;


use App\Error;
use App\Events\OrderCanceled;
use App\Events\OrderConfirmed;
use App\Events\OrderPaid;
use App\Events\OrderShipped;
use App\Exceptions\OrderException;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderGoods;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param $orderId
     * @throws OrderException
     */
    public function pay($orderId){
        $order                                      =   Order::findOrFail($orderId);
        if($order->order_status != Order::CREATED){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status),Error::order_status_error);
        }
        $order->order_status                        =   Order::PAID;
        $order->pay_status                          =   1;
        $order->pay_time                            =   new Carbon();
        $order->save();

        event(new OrderPaid($order));
    }

    /**
     * @param $orderId
     * @throws OrderException
     */
    public function shipping($orderId){
        $order                                      =   Order::findOrFail($orderId);
        if($order->order_status != Order::PAID){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status),Error::order_status_error);
        }
        $order->order_status                        =   Order::SHIPPED;
        $order->shipping_time                       =   new Carbon();
        $order->save();
        event(new OrderShipped($order));
    }

    /**
     * @param $orderId
     * @throws OrderException
     */
    public function confirm($orderId){
        $order                                      =   Order::findOrFail($orderId);
        if($order->order_status != Order::SHIPPED){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status),Error::order_status_error);
        }
        $order->order_status                        =   Order::CONFIRMED;
        $order->confirm_time                        =   new Carbon();
        $order->save();
        event(new OrderConfirmed($order));
    }

    /**
     * @param $orderId
     * @throws OrderException
     */
    public function cancel($orderId){
        $order                                      =   Order::findOrFail($orderId);
        if($order->order_status != Order::CREATED){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status),Error::order_status_error);
        }
        $order->order_status                        =   Order::CANCELED;
        $order->cancel_time                         =   new Carbon();
        $order->save();
        event(new OrderCanceled($order));
    }




}