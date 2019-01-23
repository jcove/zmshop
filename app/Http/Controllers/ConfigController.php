<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Config();
    }

    protected function validator($data){
        return Validator::make($data,[]);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }

    public function json(){

        $list                       =   $this->model->all();

        if($list){
            $data                   =   [];
            foreach ($list as $row){
                $data[$row->name] =   $row->value;
            }
        }
        $this->data                 =   $data;
        return $this->respond($this->data);

    }
}