<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\Region;
use App\Models\UserAddress;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;

class UserAddressController extends Controller
{

    use Restful;

    public function __construct()
    {
        $this->model                    =   new UserAddress();
        $this->setExceptField(['api_token','region']);
    }

    protected function validator($data){
        return Validator::make($data,[
            'consignee'             =>  'required',
            'country'               =>  'required',
         //   'province'               =>  'required',
        //    'city'               =>  'required',
        //    'district'               =>  'required',
            'address'               =>  'required',
            'phone'               =>  'required',
        ]);
    }

    protected function beforeUpdate(){
        $isDefault                  =   request()->is_default;
        if($isDefault && $isDefault==1){
            UserAddress::where(['user_id'=>Auth::id()])->whereNotIn('id',[$this->model->id])->update(['is_default'=>0]);
        }
        $this->model->user_id       =   Auth::id();
    }
    protected function prepareSave(){
        $isDefault                  =   request()->is_default;
        if($isDefault && $isDefault==1){
            UserAddress::where(['user_id'=>Auth::id()])->update(['is_default'=>0]);
        }
        $this->model->user_id       =   Auth::id();
    }
    protected function where(){
        return ['user_id'=>Auth::id()];
    }

    public function getDefault(){
        $this->model                =   $this->model->where(['user_id'=>Auth::id(),'is_default'=>UserAddress::IS_DEFAULT])->first();
        if(null==$this->model){
            $this->model            =   UserAddress::where(['user_id'=>Auth::id()])->firstOrFail();
        }
        $this->data['data']         =   $this->model;
        return $this->respond($this->data);
    }


    protected function beforeIndex(){
        $this->setTitle(trans('html.user.address'));
    }

    public function region(){
        $result                         =   [];
        if(Auth::check()){
            $address                    =   $this->model->where(['user_id'=>Auth::id(),'is_default'=>UserAddress::IS_DEFAULT])->first();
            $r                          =   Region::getByName($address->city);
            $c                          =   Country::getByName($address->country);
            $result['region']           =   $r;
            $result['country']          =   $c;
        }else{
            $r                          =   Region::find(1);
            $c                          =   Country::find(1);
            $result['region']           =   $r;
            $result['country']          =   $c;

        }
        return $this->respond($result);
    }

}