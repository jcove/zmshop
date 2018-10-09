<?php

namespace App\Http\Controllers;


use App\Models\GoodsModel;
use App\Models\GoodsModelAttribute;
use App\Models\GoodsModelSpecification;
use Illuminate\Support\Facades\Validator;

use Jcove\Restful\Restful;

class GoodsModelController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new GoodsModel();
    }
    protected function validator($data){
        $rule                           =   [];
        if(request()->method()== "PUT"){
            $rule['name']               =   'required|unique:goods_models,id,'.request()->id;
        }else{
            $rule['name']               =   'required|unique:goods_models,name';
        }
        return Validator::make($data,$rule);
    }
    protected function beforeShow(){
        $attributes                 =   GoodsModelAttribute::where('model_id',$this->model->id)->get();
        $specifications             =   GoodsModelSpecification::getListByModelId($this->model->id);
        $this->model->attrs           =   $attributes;
        $this->model->specifications       =   $specifications;
    }



}