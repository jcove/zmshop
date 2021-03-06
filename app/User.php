<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nick', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    public function getAvatarAttribute($value){
        return storage_url($value);
    }

    public function userAddress(){
        return $this->hasMany('App\Models\UserAddress','user_id','id');
    }

    public function getGenderTextAttribute(){
        switch ($this->gender){
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
            default:
                return '';
        }
    }
}
