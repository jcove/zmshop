<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          //  'pay_code'              =>  'required',
            'address_id'            =>  'numeric|exists:user_addresses,id',
        ];
    }

    public function messages(){
        return [
            'pay_code.required' => trans('validation.please_choose_payment'),
            'address_id.required'  => trans('validation.please_choose_address'),
        ];
    }
}
