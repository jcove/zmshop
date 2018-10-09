<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Payment();
    }

    protected function validator($data){
        $rule                           =   [
          'pay_code'            =>  'required',
          'icon'                =>  'required'
        ];
        return Validator::make($data,$rule);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }
}