<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/25
 * Time: 15:35
 */

namespace App\Http\Controllers\Admin;


use Jcove\Restful\Restful;

class GoodsController
{
    use Restful;
    protected $model;

    /**
     * @var $goodsModel GoodsModel 商品模型
     */
    private $goodsModel;
    /**
     * @var $goodsModelSpecifications Collection 商品模型规格
     */
    private $goodsModelSpecifications;


    public function __construct()
    {
        $this->model                    =   new Goods();
        $this->setExceptField(['galleries','attrs','specifications','specification_items','relations']);
    }
    protected function validator($data){
        return Validator::make($data,[
            'name'                          =>  'required',
            'store'                         =>  'required|numeric|min:1|max:999999',
            'model_id'                      =>  'required|numeric|exists:goods_models,id'
        ]);
    }
}