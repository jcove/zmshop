<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public static function getName($id){
        $goods                          =   static ::select('name')->firstOrFail($id);
        return $goods['name'];
    }
    public function getCoverAttribute($image){
        return storage_url($image);
    }

    public static function list($column,$operate=null,$value=null){
        $goods                                  =   static ::where($column,$operate,$value);
        if(is_array($column)){
            $goods                              =   static ::where($column);
        }
        if($operate=='in'){
            $goods                              =   static ::whereIn($column,$value);
        }
        $sort                                   =   request()->input('sort','id');
        if($sort=='synthesized' || $sort=='id') {
            $goods->orderBy('id','desc');
        }
        if($sort=='price'){
            $goods->orderBy('price','asc');
        }
        if($sort=='sales'){
            $goods->orderBy('sale_num','desc');
        }
        return $goods->paginate(request()->page_size ? request()->page_size: config('restful.page_rows'));
    }

    public static function info($id){
        return static :: where(['id'=>$id, 'status'=> 1])->first();
    }

    public function brand(){
        return $this->hasOne('App\Models\Brand','id','brand_id');
    }

    public function category(){
        return $this->hasOne('App\Models\GoodsCategory','id','category_id');
    }
    public function specification_item_price(){
        return $this->hasMany('App\Models\GoodsSpecificationItemPrice');
    }

    public static function getBrandsByIds($ids){
        return static ::whereIn('id',$ids)->select('brand_id')->get();
    }
}