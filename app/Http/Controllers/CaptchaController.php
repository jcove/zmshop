<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/3
 * Time: 9:38
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;

class CaptchaController
{
    use Restful;
    public function verify(){
        $rules = ['captcha' => 'required|captcha'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            return $this->fail(trans('message.captcha_error'),500);
        }
        else
        {
            return $this->success();
        }
    }
}