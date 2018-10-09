<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static function getByName($name){
        return static :: where('name',$name)->first();
    }
}