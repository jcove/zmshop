<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/13
 * Time: 17:22
 */

namespace App\Http\Controllers;


use App\Exceptions\OrderException;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Jcove\Restful\Restful;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Yansongda\LaravelPay\Facades\Pay;


class PayController extends Controller
{
    use Restful;


    private $service;

    /**
     * PayController constructor.
     * @param $service
     */
    public function __construct()
    {
        $this->service = new OrderService();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function paySuccess($id){

        $this->service->pay($id);

        return $this->success(trans('message.pay_success'));
    }

    public function cancel(){

    }

    public function  notification(){

        return $this->success(trans('message.pay_success'));
    }

    /**
     * @param $id
     * @return mixed
     * @throws OrderException
     */
    public function payOrder($id){
        $order                                          =   Order::findOrFail($id);
        if(!$order->canPay()){
            throw new OrderException(trans('order.order_status_not_allowed').':'.Order::orderStatusText($order->order_status),Error::order_status_error);
        }
        $array = [
            'out_trade_no'          => $order->order_id,
            'total_amount'          => $order->total_amount,
            'subject'               => '购买',
        ];

        return Pay::alipay()->web($array);
    }

    public function return(){

    }

}