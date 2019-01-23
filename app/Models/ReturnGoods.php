<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnGoods extends Model
{

    const CREATED               =   0;
    const AGREE                 =   1;
    const REFUSE                =   2;
    const SEND                  =   3;
    const FINISH                =   4;

    const REFUND                =   2;//退款
    const RETURN_PURCHASE       =   3;//退货
    const EXCHANGE_GOODS        =   1;//换货

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }

}