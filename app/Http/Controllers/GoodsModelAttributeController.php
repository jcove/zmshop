<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodsModelAttributeRequest;
use App\Models\GoodsModelAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;

class GoodsModelAttributeController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new GoodsModelAttribute();

    }

    protected function validator($data)
    {
        return Validator::make($data,[
            'name'                  =>  'required',
            'model_id'              =>  'required',
        ]);
    }
    protected function where(){
        $modelId                    =   request()->model_id;
        if(null!=$modelId){
            return ['model_id'=>$modelId];
        }
    }
}