<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsModelSpecification extends Model
{
    public function items()
    {
        return $this->hasMany('App\Models\GoodsModelSpecificationItem','specification_id');
    }

    public static function getListByModelId($modelId){
        $list                           =   static::where('model_id',$modelId)->get();
        if($list){
            foreach ($list as $k => $v){
                $list[$k]->items       =   $v->items;
            }
        }
        return $list;
    }
}