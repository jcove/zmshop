<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/12
 * Time: 11:00
 */

namespace App\Http\Controllers;


use App\Exceptions\CartException;
use App\Exceptions\PaymentException;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Cart;

use App\Models\Order;
use App\Models\OrderGoods;
use App\Services\CreateOrderService;
use App\Services\FreightService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;

/**
 * Class BuyController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    use Restful;

    private $createOrderService;
    private $orderService;

    /**
     * OrderController constructor
     * @param $createOrderService
     */
    public function __construct()
    {
        $this->model                                =   new Order();
        $this->orderService                         =   new OrderService();
    }


    /**
     * @param CreateOrderRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws CartException
     * @throws PaymentException
     */
    public function createOrder(CreateOrderRequest $request){
        $this->createOrderService                   =   new CreateOrderService();
        $this->createOrderService->setAddress($request->address_id);
        $freightService                             =   new FreightService();

        $this->createOrderService->setCarts();
        $this->createOrderService->getPromotions();
        $shippingFee                                =   $freightService->getGoodsShippingFee($this->createOrderService->getCarts(),$request->address_id);

        $this->createOrderService->setShippingFee($shippingFee);
        $this->createOrderService->calculateFee();

        $order                                      =   $this->createOrderService->create();

        $this->data['data']                         =   $order;
        return $this->respond($this->data);
    }

    public function where(){
        $where                      = ['user_id'=>Auth::id()];
        if(($orderStatus = request()->order_status)!==null){
            $where['order_status']  =   $orderStatus;
        }
        return $where;
    }

    protected function beforeIndex(){
        if($this->canJson()){
            if($list = $this->data){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->order_goods;
                        $list[$k]               =   $value;
                    }
                    $this->data         =   $list;
                }
            }
        }else{
            if($list = $this->data['list']){
                if(count($list)){
                    foreach ($list as  $k=>$value){
                        $value->order_goods;
                        $list[$k]               =   $value;
                    }
                    $this->data['list']         =   $list;
                }
            }
        }

        $this->setTitle(trans('html.order.order_list'));


    }
    protected function beforeShow(){
        if($this->canJson()){
            $this->model->order_goods;
        }
    }

    /**
     * @param $id
     * @throws PaymentException
     */
    public function getPay($id){
        $order                                      =   Order::findOrFail($id);
        $class                                      =   '\App\Payments\\'.ucwords($order->pay_code);
        if(!class_exists($class)){
            throw new PaymentException(trans('message.payment_not_exist'));
        }
        $payment                                    =   new $class;
        $this->data['pay_url']                      =   $payment->getPayUrl($order);
        return $this->respond($this->data);
    }

    public function pay(){

    }



    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function confirm($id){
        $this->orderService->confirm($id);
        return $this->success();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function cancel($id){
        $this->orderService->cancel($id);
        return $this->success();
    }

    public function toComment($id){

        $this->data['id']                 =   $id;
        $this->setTitle(trans('html.order.to_comment'));
        return $this->respond($this->data);
    }

    public function status(){
        $status                             =   [0,2,4,6,8,-1];
        $array2                             =   Order::allStatusNumber(Auth::id());
        $array[]                            =   [];
        foreach ($status as $k => $v){
            $array[$k]                            =   [
                'order_status'  =>  $v,
                'num'=>0
            ];
            foreach ($array2 as $item){
                if($item['order_status'] == $v){
                    $array[$k]['num']   =   $item['num'];
                }
            }

        }

        $this->data                         =   array_values($array);

        return $this->respond($this->data);
    }

    public function createSuccess($id){
        $order                              =   Order::findOrFail($id);

        $this->data['data']                 =   $order;
        $this->setTitle(trans('html.order.success'));
        return $this->respond($this->data);
    }
} 