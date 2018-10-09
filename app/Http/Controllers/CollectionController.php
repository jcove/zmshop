<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Collection;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Collection();
    }

    protected function validator($data){
        $rule['goods_id']               =   'required|exists:goods,id';
        return Validator::make($data,$rule);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        $goods                          =   Goods::find(request()->goods_id);
        $collection                     =   Collection::firstOrCreate([
            'goods_id'  =>  $goods->id,
            'goods_name'=>  $goods->name,
            'cover'     =>  $goods->cover,
            'user_id'   =>  Auth::id()
        ]);
        $this->data['data']             =   $collection;
        return $this->respond($this->data);

    }


    protected function where(){
        return ['user_id'=>Auth::id()];
    }

    public function cartToCollection(){
        $list                           =   Cart::where(['user_id'=>Auth::id(),'is_check'=>Cart::CHECKED])->select('goods_id','goods_name','cover')->get();
        DB::transaction(function ()use ($list){
            Cart::where(['user_id'=>Auth::id(),'is_check'=>Cart::CHECKED])->delete();
            foreach ($list as $item){
                Collection::firstOrCreate([
                    'goods_id'  =>  $item->goods_id,
                    'goods_name'=>  $item->goods_name,
                    'cover'     =>  $item->cover,
                    'user_id'   =>  Auth::id()
                ]);
            }


        });
        return $this->success();

    }

    protected function beforeIndex(){
        $this->setTitle(trans('html.user.user_collection'));
    }

    public function check($goodsId){
        $collect                            =   Collection::where(['user_id'=>Auth::id(),'goods_id'=>$goodsId])->firstOrFail();
        $this->data['data']                 =   $collect;
        return $this->respond($this->data);
    }
}