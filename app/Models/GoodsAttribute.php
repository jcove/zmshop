<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttribute extends Model
{
    public static function getFilterByGoodsIds($ids,$except=[]){
        if(is_string($ids)){
            $ids                                    =   explode(',',$ids);
        }
        $list                                       =   static ::whereIn('goods_id',$ids)->groupBy('attribute_value')->select('id','attribute_id','attribute_name','attribute_value')->get();
        $array                                      =   [];
        $keys                                       =   [];
        $i                                          =   0;

        foreach ($list as $item){
            if(!in_array($item->attribute_name,$except)){
                if(!key_exists($item->attribute_name,$keys)){
                    $keys[$item->attribute_name]                             =   $i;

                    $array[$i]['name']  =  $item->attribute_name;
                    $array[$i]['value'][] =$item->attribute_value;
                    $i++;
                }else{
                    $array[$keys[$item->attribute_name]]['value'][]= $item->attribute_value;
                }
            }


        }
        return $array;
    }
}
