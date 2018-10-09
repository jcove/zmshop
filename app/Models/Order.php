<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/17
 * Time: 14:47
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    const CREATED                       =   0;//待付款，创建
    const PAID                          =   2;//已支付，等待发货
    const SHIPPED                       =   4;//已发货，等待确认
    const CONFIRMED                     =   6;//已确认，等待评价，已完成
    const COMMENTED                     =   8;//已评价
    const RETURN                        =   10;//已评价
    const CANCELED                      =   -1;


    const PARTIAL_SHIPPED               =   1;//部分发货
    const ALL_SHIPPED                   =   2;//全部发货

    public function order_goods(){
        return $this->hasMany('App\Models\OrderGoods','order_id','id');
    }

    public static function orderStatusText($orderStatus){
        $text                           =   '';
        switch ($orderStatus){
            case Order::CREATED:
                $text                   =   trans('order.order_status.created');
                break;
            case Order::PAID:
                $text                   =   trans('order.order_status.paid');
                break;
            case Order::SHIPPED:
                $text                   =   trans('order.order_status.shipped');
                break;
            case Order::CONFIRMED:
                $text                   =   trans('order.order_status.confirmed');
                break;
            case Order::COMMENTED:
                $text                   =   trans('order.order_status.commented');
                break;
        }

        return $text;
    }

    public static function allStatusNumber($userId){
        return static ::where('user_id',$userId)->groupBy('order_status')->select(DB::raw('order_status,count(id) as num'))->get();
    }
}