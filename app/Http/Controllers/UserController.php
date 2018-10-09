<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/5
 * Time: 11:35
 */

namespace App\Http\Controllers;


use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;

class UserController extends Controller
{
    use Restful;


    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->model                            =   new User();
        $this->setExceptField(['password_confirmation','code']);
    }

    /**
     * UserController constructor.
     */


    public function update(UserRequest $request)
    {
        $this->model                                =   Auth::user();
        foreach ($request->all() as $column => $value) {
            if(!in_array($column,$this->getExceptFields()) && $column!='mobile'){
                $this->model->setAttribute($column, $value);
            }
        }
        $this->model->save();
        return $this->respond($this->model);
    }

    public function my(){
        $this->data['data']             =   Auth::user();
        $this->setTitle(trans('html.user.base_info'));
        return $this->respond($this->data);
    }

    public function safe(){
        $this->setTitle(trans('html.user.safe_info'));
        return $this->respond();
    }

    public function where(){
        if( $q= request()->q){
            $this->model                            =   $this->model->where('nick','like','%'.$q.'%');
        }
        $where                                      =   [];
        if($mobile=request()->mobile){
            $where['mobile']                        =   $mobile;
        }
        return $where;
    }
}