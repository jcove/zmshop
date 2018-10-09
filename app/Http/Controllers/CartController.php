<?php

namespace App\Http\Controllers;

use App\Exceptions\GoodsException;
use App\Exceptions\ParamException;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\GoodsSpecificationItemPrice;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Jcove\Restful\Restful;

class CartController extends Controller
{
    use Restful;
    protected $model;

    private $goodsModel;
    private $goodsSpecItemPrices;
    private $inputGoodsSpecItemPrice;
    private $cart;
    private $newNum;

    public function __construct()
    {
        $this->model                    =   new Cart();
    }

    protected function validator($data){
        return Validator::make($data,[
            'goods_id'                  =>  'required|numeric',
            'item_id'        =>  [
                'numeric',
                Rule::exists('goods_specification_item_prices','id')->where(function ($query) use ($data){
                    $query->where(['goods_id'=>$data['goods_id']]);
                })
                ],
            'num'                       =>  'required',
        ]);
    }
    protected function prepareSave(){
        $this->validateStore();
        $this->model->user_id           =   Auth::id();
    }
    protected function where(){
        request()->offsetSet('all','1');
        $where['user_id']               =   Auth::id();
        if(($isCheck = request()->is_check) !== null){
            $where['is_check']          =   $isCheck;
        }
        return $where;
    }

    protected function beforeIndex(){
        $carts                          =   [];
        if(!$this->canJson()){
            $carts                      =   $this->data['list'];
        }else{
            $carts                      =   $this->data;
        }
        if (count($carts) > 0){
            foreach ($carts as $key=>$cart){
                $goods                  =   Goods::find($cart->goods_id);
                if($goods){
                    $cart->price            =   $goods->price;
                }else{
                    $cart->status           =   -1;
                    $cart->save();
                }

                if($this->canJson()){
                    $cart->item             =   $cart->item;
                }
                $carts->offsetSet($key,$cart);
            }
        }
        if(!$this->canJson()){
            $this->data['list']         =   $carts;
        }else{
            $this->data                 =   $carts;
        }
    }
    /**
     * @throws \App\Exceptions\GoodsException
     */
    protected function validateStore(){
        $goodsSpecItemId                =   request()->item_id;
        $goodsId                        =   request()->goods_id;
        $num                            =   request()->num;
        $this->goodsModel               =   Goods::findOrFail(request()->goods_id);
        $this->goodsSpecItemPrices      =   GoodsSpecificationItemPrice::where('goods_id',$goodsId)->get();
        if(empty($goodsSpecItemId) && count($this->goodsSpecItemPrices) > 0){
            throw new GoodsException(trans('message.spec_item_id_required'));
        }else{
            if(count($this->goodsSpecItemPrices) > 0){
                $array                      =   [];

                foreach ($this->goodsSpecItemPrices as $itemPrice){
                    $array[]                            =   $itemPrice->id;
                    if($itemPrice->id == $goodsSpecItemId){
                        $this->inputGoodsSpecItemPrice  =   $itemPrice;
                    }
                }
                if(!in_array($goodsSpecItemId,$array)){
                    throw new GoodsException(trans('message.spec_item_id_invalid'));
                }

                $this->cart                             =   Cart::where(['user_id'=>Auth::id(),'goods_id'=>$goodsId,'goods_spec_item_id'=>$goodsSpecItemId])->first();
                $this->newNum                           =   $this->cart ? $this->cart->num + $num : $num;
                if($this->newNum > $this->inputGoodsSpecItemPrice->store){
                    throw new GoodsException(trans('message.store_not_enough',['attribute'=>$this->inputGoodsSpecItemPrice->store]));
                }
            }else{
                $this->cart                             =   Cart::where(['user_id'=>Auth::id(),'goods_id'=>$goodsId])->first();
                $this->newNum                           =   $this->cart ? $this->cart->num + $num : $num;
                if($this->newNum > $this->goodsModel->store){
                    throw new GoodsException(trans('message.store_not_enough',['attribute'=>$this->goodsModel->store]));
                }
            }

        }


    }

    /**
     * 添加购物车
     * @param CartRequest $request
     * @throws \App\Exceptions\GoodsException
     * @return mixed
     */
    public function add(CartRequest $request){
        $goodsSpecItemId                        =   $request->item_id;
        $goodsId                                =   $request->goods_id;
        $num                                    =   $request->num;
        $this->validateStore();
        $goods                              =   Goods::findOrFail($goodsId);
        if($this->cart){
            $this->cart->num                    =   $this->newNum;
            $this->cart->price                  =   $goods->price;
            $this->cart->save();
        }else{
            $this->cart                         =   new Cart();
            $this->cart->goods_id               =   $goodsId;
            if($goodsSpecItemId){
                $spec                           =   GoodsSpecificationItemPrice::find($goodsSpecItemId);
                if(empty($spec)){
                    throw new GoodsException(trans('message.specification_not_exist'));
                }
                $this->cart->goods_spec_item_id  =   $goodsSpecItemId;
                $this->cart->goods_spec_item_name=   $spec->name;
            }

            $this->cart->goods_name             =   $goods->name;
            $this->cart->num                    =   $num;
            $this->cart->user_id                =   Auth::id();
            $this->cart->cover                  =   $goods->cover;
            $this->cart->price                  =   $goods->price;
            $this->cart->save();
        }
        return $this->respond(['data'=>$this->cart]);
    }

    public function check($id){
        $this->model                            =   Cart::where(['id'=>$id,'user_id'=>Auth::id()])->firstOrFail();
        $this->model->is_check                  =   $this->model->is_check == 1 ? 0:1;
        $this->model->save();
        $this->data['info']                     =   $this->model;
        return $this->respond($this->data);
    }

    public function checkAll(){
        $isCheck                                =   request()->is_check;
        DB::table('carts')
            ->where('user_id',Auth::id())
            ->update(['is_check'=>$isCheck]);
        return $this->success();
    }

    public function submit(){
        $list                                   =    Cart::where(['is_check'=>1,'status'=>1, 'user_id'=>Auth::id()])->get();

        if (count($list) > 0){
            foreach ($list as $key=>$cart){
                $goods                  =   Goods::find($cart->goods_id);
                $cart->price            =   $goods->price;
                $list->offsetSet($key,$cart);
            }
        }
        $address                                =   UserAddress::getDefault();
        $this->data['list']                     =   $list;
        $this->data['address']                  =   $address;
        return $this->respond($this->data);
    }
    public function deleteChecked(){
        Cart::where(['user_id'=>Auth::id(),'is_check'=>Cart::CHECKED])->delete();
        return $this->success();
    }
}
