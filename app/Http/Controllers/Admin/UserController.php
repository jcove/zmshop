<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/5
 * Time: 11:35
 */

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
    protected function beforeUpdate(){
        $this->validator(request()->all())->validate();
        if(request()->password == $this->model->getOriginal('password')) {
            $this->model->offsetUnset('password');
        }elseif($this->model->getAttribute('password')){
            $this->model->password           =    bcrypt(request()->password);
        }

    }

    protected function validator($data){
        if(request()->method()== "PUT"){
            $rules['mobile']                        =   'required|unique:users,id,'.request()->id;
            $rules['password']                      =   'required|between:6,60|confirmed';
            $rules['password_confirmation']         =   'required|between:6,60';
        }else{
            $rules['mobile']                        =   'required|unique:admin_users';
            $rules['password']                      =   'required|between:6,60|confirmed';
            $rules['password_confirmation']         =   'required|between:6,60';
        }
        $rules['nick']                              =   'required';

        return Validator::make($data,$rules);
    }
    public function export(){
        $where                              =   $this->where();
        $list                               =   $this->model->where($where)->get();
        if($list){
            $this->data['list']             =   $list;
        }else{
            return $this->fail(trans('message.data_not_found'),404);
        }
        $filename                           =   '用户'. (new Carbon())->format('Y-m-d-H-i-s');
        return response()
            ->view('pc.admin.user.export', $this->data, 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Type', 'application/force-download')
            ->header('Content-Disposition', 'filename='.$filename.'.xls')
            ->header('Content-Type', 'application/vnd.ms-excel');
    }
}