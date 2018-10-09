<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/3
 * Time: 10:08
 */

namespace App\Http\Controllers;


use App\Error;
use App\Exceptions\SmsException;
use App\Models\SmsCode;
use GuzzleHttp\Client;
use Jcove\Restful\Restful;
use LaraMall\AlidySms\Facades\Sms;

class SmsController
{
    use Restful;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws SmsException
     */
    public function send(){
        $mobile                                     =   request()->mobile;
        if(!check_mobile($mobile)){
            return response()->json(['status'=>false,'msg'=>'手机号非法']);
        }
        $SmsCode                                    =   SmsCode::getCode($mobile);
        if($SmsCode){
            return $this->fail(trans('message.wait_a_moment'),500);
        }

        $SmsCode                                    =   new SmsCode();
        $SmsCode->code                              =   rand(100000, 999999);
        $SmsCode->status                            =   0;
        $SmsCode->mobile                            =   $mobile;

        if(config('app.debug')){
            $SmsCode->save();
            return $this->success($SmsCode->code);
        }
        $content                                    =   '您好，您的验证码是：'.$SmsCode->code.'【'.config('sms.5c.sign').'】';
        $result                                     =   $this->sendSMS($mobile,$content);

        if(strpos($result,"success")>-1) {
            $SmsCode->save();
            return $this->success();
        } else {
            throw new SmsException($result,Error::sms_error);
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws SmsException
     */
    public function verify(){
        $mobile                                     =   request()->mobile;
        $code                                       =   request()->code;
        if(!SmsCode::verify($mobile,$code)){
            throw new SmsException(trans('message.sms_code_error'),Error::sms_error);
        }
        return $this->success();
    }

    protected function sendSMS($mobile,$content)
    {
        //发送链接（用户名，密码，apikey，手机号，内容）
        $url = "http://m.5c.com.cn/api/send/index.php?";  //如连接超时，可能是您服务器不支持域名解析，请将下面连接中的：【m.5c.com.cn】修改为IP：【115.28.23.78】
        $data=array
        (
            'username'=>config('sms.5c.username'),
            'password_md5'=>md5(config('sms.5c.password')),
            'apikey'=> config('sms.5c.api_key'),
            'mobile'=>$mobile,
            'content'=>urlencode($content),
            'encode'=>config('sms.5c.encode'),
        );

        $client                                 =   new Client();

        $response = $client->post($url,['form_params'=>$data]);
        //print_r($data); //测试
        $body = $response->getBody();

        return $body->getContents();
    }
}