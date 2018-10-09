<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = ['id'];

    public function getCoverAttribute($value){
        return storage_url($value);
    }
}