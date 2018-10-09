<?php

namespace App\Http\Controllers;

use App\Error;
use App\Exceptions\OrderException;
use App\Models\Delivery;
use App\Models\DeliveryGoods;
use App\Models\Express;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Delivery();
    }

    protected function validator($data){
        $rule                           =   [
            'express_code'              =>  'required',
            'express_sn'                =>  'required'
        ];
        return Validator::make($data,$rule);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }

    /**
     * @param $orderId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws OrderException
     */
    public function delivery($orderId){
        $this->validator(request()->all())->validate();
        $order                                           =   Order::findOrFail($orderId);
        $expressCode                                     =  request()->express_code;
        $expressSn                                       =  request()->express_sn;
        $express                                         =  Express::where('express_code',$expressCode)->firstOrFail();
        if($order->order_status != Order::PAID){
            if(!($order->order_status > Order::PAID && $order->shipping_status < Order::ALL_SHIPPED)){
                throw new OrderException(trans('order.order_status_error'),Error::order_status_error);
            }

        }
        //商品
        $goodses                                           =  request()->goods;
        $delivery                                        =   new Delivery();
        $delivery->order_id                              =   $order->id;
        $delivery->delivery_sn                           =   $this->createDeliverySn();
        $delivery->consignee                             =   $order->consignee;
        $delivery->country                               =   $order->country ? : '';
        $delivery->province                              =   $order->province;
        $delivery->country                               =   $order->consignee;
        $delivery->city                                  =   $order->city;
        $delivery->district                              =   $order->district? : '';
        $delivery->town                                  =   $order->town ? : '';
        $delivery->address                               =   $order->address;
        $delivery->phone                                 =   $order->phone;
        $delivery->express_code                          =   $expressCode;
        $delivery->express_sn                            =  $expressSn;
        $delivery->express_name                          =  $express->name;
        $order->express_code                          =   $expressCode;
        $order->express_sn                            =  $expressSn;
        $order->express_name                          =  $express->name;
        $order->order_status                        =   Order::SHIPPED;
        $order->shipping_time                           =   new Carbon();

        DB::transaction(function ( ) use ($delivery,$order,$goodses){
            $delivery->save();
            if($order->order_goods){
                $count                                              =   0;
                foreach ($goodses as $item) {
                    foreach ($order->order_goods as $key =>$goods){
                        if($goods->goods_id == $item){
                            $goods->delivery_id                     =   $delivery->id;
                            $goods->is_shipping                     =   1;
                            $goods->save();
                            $order->order_goods[$key]=$goods;
                            $DeliveryGoods                          =   new DeliveryGoods();
                            $DeliveryGoods->goods_id                =   $goods->id;
                            $DeliveryGoods->goods_name              =   $goods->goods_name;
                            $DeliveryGoods->num                     =   $goods->num;
                            $DeliveryGoods->price                   =   $goods->final_price;
                            $DeliveryGoods->goods_spec_item_name    =   $goods->goods_spec_item_name;
                            $DeliveryGoods->delivery_id             =   $delivery->id;
                            $DeliveryGoods->save();

                        }

                    }
                }
                foreach ($order->order_goods as $key =>$goods){
                    if($goods->is_shipping > 0){
                        $count++;
                    }
                }
                if(count($order->order_goods) == $count){
                    $order->shipping_status                         =   Order::ALL_SHIPPED;
                }else{
                    $order->shipping_status                         =   Order::PARTIAL_SHIPPED;
                }

            }
            $order->save();
        });
        return $this->success();
        
    }

    protected function beforeIndex(){
        if($this->canJson()){
            if($list = $this->data){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->delivery_goods;
                        $list[$k]               =   $value;
                    }
                    $this->data         =   $list;
                }
            }
        }else{
            if($list = $this->data['list']){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->delivery_goods;
                        $list[$k]               =   $value;
                    }
                    $this->data['list']         =   $list;
                }
            }
        }
    }

    protected function createDeliverySn(){
        return '30'.(new \Carbon\Carbon())->format('YmdHis').time();
    }
}