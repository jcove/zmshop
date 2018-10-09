<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserAddress extends Model
{
    const  IS_DEFAULT               =   1;

    public static function getByIdAndUserId( $id, $userId = null){
        if(null==$userId){
            $userId                                 =   Auth::id();
        }
        return static :: where(['id'=>$id,'user_id'=>$userId])->firstOrFail();
    }

    public static function getDefault( $userId = null ){
        if(null==$userId){
            $userId                                 =   Auth::id();
        }
        $address = static :: where(['user_id'=>$userId,'is_default'=>1])->first();
        if(empty($address)){
            $address                                =   static :: where(['user_id'=>$userId])->first();
        }
        return $address;
    }
}