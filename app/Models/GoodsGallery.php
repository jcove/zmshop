<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsGallery extends Model
{
    public function getPathAttribute($value){
        return storage_url($value);
    }
}
