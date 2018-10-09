<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Region();
    }

    protected function validator($data){
        return Validator::make($data,[]);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }

    public function children($id){
        $this->data['data']                 =   Region::children($id);
        return $this->respond($this->data);
    }

}