<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    const CONFIRMED             =   1;
    const UN_CONFIRMED          =   0;

    public function delivery_goods(){
        return $this->hasMany('App\Models\DeliveryGoods','delivery_id','id');
    }
}