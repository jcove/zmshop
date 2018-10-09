<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSpecification extends Model
{

    protected function setSpecificationValueAttribute($value){
        $this->attributes['specification_value']=implode(',',$value);

    }

    protected function getSpecificationValueAttribute($valuse){
        return explode(',',$valuse);
    }
}
