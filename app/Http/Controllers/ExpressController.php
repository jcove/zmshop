<?php

namespace App\Http\Controllers;

use App\Models\Express;
use GuzzleHttp\Client;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class ExpressController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Express();
    }

    protected function validator($data){
        return Validator::make($data,[]);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }
    public function get(){
        $com                        =   request()->com;
        $postId                     =   request()->post_id;
        $client                     =   new Client();
        return $client->get('https://m.kuaidi100.com/query?postid='.$postId.'&type='.$com);
    }
}