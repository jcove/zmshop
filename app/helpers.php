<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/11
 * Time: 10:58
 */
if(!function_exists('cartesian')){
    function cartesian($array,$glue = null){
        $result                             =   array_shift($array);
        while($arr2 = array_shift($array)){
            $arr1                           =   $result;
            $result                         =   array();
            foreach($arr1 as $v){
                foreach($arr2 as $v2){
                    if(!is_array($v))$v     =   array($v);
                    if(!is_array($v2))$v2   =   array($v2);
                    $array3                 =   array_merge_recursive($v,$v2);
                    sort($array3);
                    if($glue){
                        $result[]           =   implode($glue,$array3);
                    }else{
                        $result[]           =   $array3;
                    }


                }
            }
        }
        return $result;
    }
}
if(!function_exists('collect_to_array')){
    function collect_to_array($collect,$key='id'){
        if($collect instanceof \Illuminate\Support\Collection){
            $array                          =   [];
            foreach ($collect as $item){
                $array[]                    =   $item->$key;
            }
            return $array;
        }
        return null;
    }
}

if(!function_exists('adp')){
    function adp($position){
        $ad = \Jcove\Ad\Models\Ad::where('position',$position)->first();
        if($ad){
            $html                           =   '';
            if($ad->type == \Jcove\Ad\Models\Ad::IMAGE){
                $html                       .=   '<div class="adp">';
                $html                       .=  '<img src="'.storage_url($ad->code).'">';
                $html                       .=  '</div>';
            }
            if ($ad->type == \Jcove\Ad\Models\Ad::CODE){
                $html                       =   $ad->code;
            }
            return $html;
        }
    }
}
if(!function_exists('check_mobile')){
    function check_mobile($str)
    {
        $pattern = '/^1[34578]{1}\d{9}$/';
        if (preg_match($pattern,$str))
        {
            Return true;
        }
        else
        {
            Return false;
        }
    }
}
