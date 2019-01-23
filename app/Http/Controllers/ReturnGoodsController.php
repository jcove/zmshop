<?php

namespace App\Http\Controllers;

use App\Error;
use App\Exceptions\ValidateException;
use App\Http\Requests\ReturnGoodsSend;
use App\Models\Express;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\ReturnGoods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class ReturnGoodsController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new ReturnGoods();
    }

    protected function validator($data){
        $rule                           =   [
            'order_id'                  =>  [
                'required',
                Rule::exists('orders','id')->where(function ($query) {
                    $query->where('order_status', '>=',Order::PAID)->where('user_id',Auth::id());
                }),
            ],
            'goods_id'                  =>  'required',
            'reason'                    =>  'required|between:1,191',
            'refund_money'              =>  'required|numeric',
            'type'                      =>  'numeric',
            'goods_spec_item_id'        =>  'numeric'
        ];
        return Validator::make($data,$rule);
    }

    /**
     * @throws ValidateException
     */
    protected function prepareSave(){
        $orderId                        =   request()->order_id;
        $goodsId                        =   request()->goods_id;
        $goodsSpecItemId                =   request()->input('goods_spec_item_id',0);
        //是否已申请过退换货
        $returnGoods                    =   ReturnGoods::where('order_id',$orderId)->where('goods_id',$goodsId)->where('goods_spec_item_id',$goodsSpecItemId)->whereIn('status',[ReturnGoods::CREATED,ReturnGoods::AGREE])->first();
        if($returnGoods){
            throw new ValidateException(trans('message.return_goods_exist'),Error::refund_money_error);
        }

        $refundMoney                    =   request()->refund_money;

        $orderGoods                     =   OrderGoods::where(['order_id'=>$orderId,'goods_id'=>$goodsId,'goods_spec_item_id'=>$goodsSpecItemId])->firstOrFail();

        if($refundMoney > $orderGoods->final_price*$orderGoods->num){
            throw new ValidateException(trans('message.refund_money_error'),Error::refund_money_error);
        }

        $this->model->return_sn         =   $this->createReturnSn();
        $this->model->goods_amount       =   $orderGoods->final_price*$orderGoods->num;
        $this->model->goods_spec_item_id=   $orderGoods->goods_spec_item_id;
        $this->model->goods_spec_item_name = $orderGoods->goods_spec_item_name;
        $this->model->cover                 =   $orderGoods->cover;
        $this->model->goods_name            =   $orderGoods->goods_name;
        $this->model->user_id               =   Auth::id();

    }

    protected function saved(){
        $orderId                        =   request()->order_id;
        $goodsId                        =   request()->goods_id;
        $goodsSpecItemId                =   request()->input('goods_spec_item_id',0);
        $orderGoods                     =   OrderGoods::where(['order_id'=>$orderId,'goods_id'=>$goodsId,'goods_spec_item_id'=>$goodsSpecItemId])->first();
        if($orderGoods){
            $orderGoods->is_return      =   OrderGoods::RETURN_APPLY;
            $orderGoods->save();
        }
    }

    protected function where(){
        $where['user_id']               =   Auth::id();
        if(($status = request()->status) !==null){
            $where['status']            =   $status;
        }
        return $where;
    }

    protected function beforeIndex(){
        $this->setTitle(trans('html.user.user_return_goods'));
        if($this->canJson()){
            $list                       =   $this->data;
            foreach ($list as $key => $value){
                $value->user;
                $list->offsetSet($key,$value);
            }
            $this->data                 =   $list;
        }
    }
    protected function createReturnSn(){
        return '50'.(new \Carbon\Carbon())->format('YmdHis').time();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws ValidateException
     */
    public function check(){
        $orderId                        =   request()->order_id;
        $goodsId                        =   request()->goods_id;
        $goodsSpecItemId                =   request()->input('goods_spec_item_id',0);
        //是否已申请过退换货
        $returnGoods                    =   ReturnGoods::where('order_id',$orderId)->where('goods_id',$goodsId)->where('goods_spec_item_id',$goodsSpecItemId)->whereIn('status',[ReturnGoods::CREATED,ReturnGoods::AGREE])->first();
        if($returnGoods){
            throw new ValidateException(trans('message.return_goods_exist'),Error::refund_money_error);
        }
        return $this->success();
    }

    public function send(ReturnGoodsSend $request, $id)
    {
        $this->model                    =   $this->model->findOrFail($id);
        if($this->model->status == ReturnGoods::AGREE){
            $this->model->express_sn    =   $request->express_sn;
            $this->model->express_code  =   $request->express_code;
            if($request->express_code){
                $express                =   Express::where('express_code',$request->express_code)->first();
                if($express){
                    $this->model->express_name = $express->name;
                }
            }
            $this->model->status        =   ReturnGoods::SEND;
            $this->save();
        }
        $this->data['data']             =   $this->model;
        return $this->respond($this->data);

    }

    public function create(){
        $orderId                        =   request()->order_id;
        $goodsId                        =   request()->goods_id;
        $orderGoods                     =   OrderGoods::where(['order_id'=>$orderId,'goods_id'=>$goodsId])->firstOrFail();
        $this->data['order_goods']      =   $orderGoods;
        return $this->respond($this->data);

    }
}