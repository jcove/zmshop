<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Config extends Model
{
    public static function config()
    {
        foreach (static::all() as $config) {
            config([$config->name => $config->value]);
        }
    }

    public function getValueAttribute($value){
        if($this->getAttribute('type') == 'image') {
            return storage_url($value);
        }
        return $value;
    }
}