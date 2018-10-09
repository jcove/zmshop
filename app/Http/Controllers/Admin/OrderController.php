<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/12
 * Time: 11:00
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\CartException;
use App\Exceptions\PaymentException;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Services\CreateOrderService;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jcove\Admin\Facades\Admin;
use Jcove\Restful\Restful;

/**
 * Class BuyController
 * @package App\Http\Controllers
 */
class OrderController
{
    use Restful;

    private $createOrderService;
    private $orderService;

    /**
     * OrderController constructor.
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
        $order                                      =   $this->createOrderService->create();
        $this->data['data']                         =   $order;
        return $this->respond($this->data);
    }

    public function where(){
        $where                          =   [];


        if(($orderStatus = request()->order_status) !==null){
            $where['order_status']  =   $orderStatus;
        }
        if($phone = request()->phone){
            $where['phone']         =   $phone;
        }
        if($order_sn = request()->order_sn){
            $where['order_sn']      =   $order_sn;
        }
        if($country = request()->country){
            $where['country']      =   $country;
        }
        if($province = request()->province){
            $where['province']      =   $province;
        }
        if($city = request()->city){
            $where['city']      =   $city;
        }
        if($district = request()->district){
            $where['district']      =   $district;
        }
        if($consignee = request()->consignee){
            $this->model            =   $this->model->where('consignee','like','%'.$consignee.'%');
        }
        if($depot = request()->depot){
            $list               =   OrderGoods::where('country',$depot)->pluck('order_id');
            $this->model            =   $this->model->whereIn('id',$list);
        }
        return $where;
    }

    protected function beforeIndex(){
        if(request()->acceptsJson() && request()->ajax()){
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \App\Exceptions\OrderException
     */
    public function pay($id){
        $this->orderService->pay($id);
        return $this->success();
    }

    public function shipping(){

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



    public function status(){
        $this->data                         =   Order::allStatusNumber(Auth::id());
        return $this->respond($this->data);
    }

    public function export(){
        $where                              =   $this->where();
        $list                               =   $this->model->where($where)->get();
        if($list){
            $this->data['list']             =   $list;
        }else{
            return $this->fail(trans('message.data_not_found'),404);
        }
        $filename                           =   '订单'. (new Carbon())->format('Y-m-d-H-i-s');
        return response()
            ->view('pc.admin.order.export', $this->data, 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Type', 'application/force-download')
            ->header('Content-Disposition', 'filename='.$filename.'.xls')
            ->header('Content-Type', 'application/vnd.ms-excel');
    }

} 