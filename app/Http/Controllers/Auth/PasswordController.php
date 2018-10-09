<?php

namespace App\Http\Controllers\Auth;

use App\Error;
use App\Exceptions\SmsException;
use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordPostRequest;
use App\Models\SmsCode;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;


class PasswordController extends Controller
{
    use Restful;
    use ResetsPasswords;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param PasswordPostRequest $request
     * @throws UserException
     */
    public function modify(PasswordPostRequest $request){
        $oldPassword                            =   $request->old_password;
        $user                                   =   User::findOrFail($request->user()->id);

        if(!password_verify($oldPassword,$user->password)){
            throw new UserException(trans('message.old_password_error'));
        }
        $user->password                         =   bcrypt($request->password);
        $user->save();
        return $this->respond(Auth::user());
    }

    public function showForgetForm(){
        $this->setTitle(trans('message.forget_password'));
        return $this->respond();
    }

    /**
     * @param Request $request
     * @throws SmsException
     */
    public function reset(Request $request){
        $this->validate($request, $this->rules());
        $mobile                                 =   $request->mobile;
        $code                                   =   $request->code;
        $password                               =   $request->password;
        if(!SmsCode::verify($mobile,$code)){
            throw new SmsException(trans('message.sms_code_error'),Error::sms_error);
        }
        $user                                   =   User::where('mobile',$mobile)->firstOrFail();
        $user->password                         =   bcrypt($password);
        $user->save();
        return $this->success();
    }

    protected function rules()
    {
        return [
            'code' => 'required',
            'mobile' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'mobile', 'password', 'password_confirmation', 'sms_code'
        );
    }
}
