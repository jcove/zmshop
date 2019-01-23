<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public static function getFilterByCategoryIds($ids){
        if(is_string($ids)){
            $ids                                    =   explode(',',$ids);
        }
        $list                                       =   static ::whereIn('category_id',$ids)->groupBy('id')->get();
        return $list;

    }



    public function getLogoAttribute($value){
        return storage_url($value);
    }
}