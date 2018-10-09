<?php

namespace App\Http\Controllers;

use App\Models\GoodsModelSpecification;
use App\Models\GoodsModelSpecificationItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

/**
 * Class GoodsModelSpecificationController
 * @package App\Http\Controllers
 */
class GoodsModelSpecificationController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new GoodsModelSpecification();
        $this->setExceptField(['items']);
    }

    protected function validator($data){
        return Validator::make($data,[
            "name"          =>  'required',
            "model_id"      =>  'required|exists:goods_models,id'
        ]);
    }

    protected function beforeShow(){
        $items                          =   $this->model->items;
        $this->model->items             =   $items;
    }
    protected function beforeIndex(){
        if($this->data instanceof LengthAwarePaginator){
            foreach ($this->data as $key => $value){
                $items                  =   $value->items;
                $value->items           =   $items;
                $this->data->put($key,$value);
            }
        }
    }
    protected function where(){
        $modelId                    =   request()->model_id ? : 0;
        if(null!=$modelId){
            return ['model_id'=>$modelId];
        }
    }
    protected function prepareSave(){

    }

    protected function saved(){
        $items                                              =   request()->items;
        Validator::make(request()->all(),[
            "items"          =>  'required'
        ])->validate();

        if(is_array($items)){
            foreach ($items as $item){
                $goodsModelSpecificationItem                    =   new GoodsModelSpecificationItem();
                $goodsModelSpecificationItem->name              =   $item;
                $goodsModelSpecificationItem->specification_id  =   $this->model->id;
                $goodsModelSpecificationItem->model_id          =   request()->model_id;
                $goodsModelSpecificationItem->save();
            }
        }else{
            $goodsModelSpecificationItem                        =   new GoodsModelSpecificationItem();
            $goodsModelSpecificationItem->name                  =   $items;
            $goodsModelSpecificationItem->specification_id      =   $this->model->id;
            $goodsModelSpecificationItem->model_id              =   request()->model_id;
            $goodsModelSpecificationItem->save();
        }
    }

    protected function updated(){
        GoodsModelSpecificationItem::where('specification_id',$this->model->id)->delete();
        $this->saved();
    }


}