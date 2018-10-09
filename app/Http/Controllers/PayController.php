<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/13
 * Time: 17:22
 */

namespace App\Http\Controllers;


use App\Services\OrderService;
use Exception;
use Jcove\Restful\Restful;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

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
        if(!isset($_GET['paymentId'], $_GET['PayerID'])){
            die();
        }
        $paypal                         =   new ApiContext(
            new OAuthTokenCredential( config('payment.paypal.client_id'),config('payment.paypal.secret'))
        );

        $paymentID = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];

        $payment = Payment::get($paymentID, $paypal);

        $execute = new PaymentExecution();
        $execute->setPayerId($payerId);
        ini_set('max_execution_time','100');
        try{
            $result = $payment->execute($execute, $paypal);
            $this->service->pay($id);

        }catch(Exception $e){
            dump($e->getMessage());
            die($e);
        }

        return $this->success(trans('message.pay_success'));
    }

}