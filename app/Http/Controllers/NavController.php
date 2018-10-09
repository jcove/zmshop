<?php

namespace App\Http\Controllers;

use App\Models\Nav;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class NavController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Nav();
    }

    protected function validator($data){
        return Validator::make($data,[]);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }
}