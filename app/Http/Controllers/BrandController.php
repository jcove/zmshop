<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsCategory;
use Illuminate\Support\Facades\Validator;
use Jcove\Restful\Restful;

class BrandController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Brand();
    }

    protected function validator($data){
        return Validator::make($data,[
           'name'                       =>  'required'
        ]);
    }

    protected function where(){
        $where                          =   [];
        if($categoryId = request()->category_id){
            $categoryId                 =   GoodsCategory::getAllCategoryId($categoryId);
            $this->model                =   $this->model->whereIn('category_id',$categoryId);
        }
        return $where;
    }




}