<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/17
 * Time: 14:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    const COMMENTED                 =   1;
    const UNCOMMENT                 =   0;

    const RETURN_APPLY              =   1;
    const RETURN_FINISH             =   4;

    public function goods(){
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }
}