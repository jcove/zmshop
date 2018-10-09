<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $data                           =  $this->all();
        return [
            'goods_id'                  =>  'required|numeric',
            'goods_spec_item_id'        =>  [
                'numeric',
                Rule::exists('goods_specification_item_prices','id')->where(function ($query) use ($data){
                    $query->where(['goods_id'=>$data['goods_id']]);
                })
            ],
            'num'                       =>  'required|numeric|max:99999'
        ];
    }
}