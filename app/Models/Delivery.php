<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $guarded = ['id'];

    public function delivery_goods(){
        return $this->hasMany('App\Models\DeliveryGoods','delivery_id','id');
    }
}