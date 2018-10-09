<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class SuggestionController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new Suggestion();
        $this->setTitle(trans('html.user.feedback'));
    }

    protected function validator($data){
        $rule['content']                =   'required';
        return Validator::make($data,$rule);
    }

    protected function prepareSave(){
        $user                           =   Auth::user();
        $this->model->user_id           =   $user->id;
        $this->model->nick              =   $user->nick;
        $this->model->avatar            =   $user->avatar;

    }
    protected function where(){
        return [];
    }
}