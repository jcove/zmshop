<?php

namespace App\Http\Controllers;

use App\Error;
use App\Exceptions\OrderException;
use App\Models\Delivery;
use App\Models\DeliveryGoods;
use App\Models\Express;
use App\Models\Order;
use App\Services\DeliveryService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $where['user_id']                               =   Auth::id();
        if($orderId = request()->order_id){
            $where['order_id']                          =   $orderId;
        }
        if($isConfirm = request()->is_confirm){
            $where['is_confirm']                          =   $isConfirm;
        }
        return $where;
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

    public function confirm($id){
        $deliveryService                        =   new DeliveryService();
        $this->data                             =   $deliveryService->confirm($id);
        return $this->respond($this->data);
    }
}