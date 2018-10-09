<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jcove\Restful\Restful;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use Restful;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function validator(array $data)
    {
        $rule                           =   [];


        $rule['password']               =   'required|string|min:6|confirmed';
        $rule['mobile']                 =   'required|unique:users,mobile';
      //  $rule['nick']                   =   'required';
        return Validator::make($data, $rule);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'mobile' => $data['mobile'],
            'nick'  => isset($data['nick']) ? $data['nick'] : $data['mobile'],
            'password' => bcrypt($data['password']),
        ]);
    }
    protected function registered(Request $request, $user)
    {
        $user->generateToken();

        return $this->respond($user);
    }

    public function showRegistrationForm(){
        $this->data['title']                =   trans('message.user_register');
        return $this->respond($this->data);
    }

}
