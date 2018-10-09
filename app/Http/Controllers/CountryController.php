<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Country();
    }

    protected function validator($data){
        $rules                          =   [
            'name'              =>  'required|unique:countries,name,'.$this->model->id
        ];
        return Validator::make($data,$rules);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }
    protected function sort($model){
        if($model){
            return $model->orderBy('id','asc');
        }


    }
}