<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{

    const CHECKED                           =   1;
    const UNCHECK                           =   0;
    public static function getUserCarts( $userId = null){
        $userId                             =   $userId ? : Auth::id();
        return static :: where(['user_id' => $userId])->get();
    }

    public static function getUserCheckedCarts($userId = null){
        $userId                             =   $userId ? : Auth::id();
        return static :: where(['user_id' => $userId,'is_check'=>Cart::CHECKED,'status'=>1])->get();
    }

    public function goods(){
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }

    public function specification(){
        return $this->hasOne('App\Models\GoodsSpecificationItemPrice','id','goods_spec_item_id');
    }

    public static function clearChecked( $userId = null){
        $userId                             =   $userId ? : Auth::id();
        return static :: where(['user_id' => $userId,'is_check'=>Cart::CHECKED])->delete();
    }
    public function item()
    {
        return $this->hasOne('App\Models\GoodsSpecificationItemPrice','id','goods_spec_item_id');
    }
}