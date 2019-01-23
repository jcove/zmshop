<?php

namespace App\Http\Controllers;

use App\Exceptions\ParamException;
use App\Http\Requests\GoodsCategoryRequest;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsAttribute;
use App\Models\GoodsCategory;
use Illuminate\Contracts\Support\Arrayable;
use Jcove\Restful\Restful;

class GoodsCategoryController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new GoodsCategory();
    }

    /**
     * 列表查询条件
     * @return array
     */
    protected function where(){
        $isShow                         =   request()->is_show;
        $isRecommend                    =   request()->is_recommend;
        $where                          =   [];
        if(null!=$isShow){
            $where['is_show']           =   $isShow;
        }
        if(null!=$isRecommend){
            $where['is_recommend']      =   $isRecommend;
        }
        return $where;
    }

    protected function sort(){

        $sort                           =   request()->input('sort','id');
        if($sort=='synthesized'){
            $sort                       =   'id';
        }
        return $this->model->orderBy($sort,'asc');
    }

    public function children($id){
        $this->data                     =  GoodsCategory::getChildren($id) ;
        return $this->respond($this->data);
    }
    public function brothers($id){
        $category                       =   $this->model->findOrFail($id);
        $brothers                       =   GoodsCategory::getChildren($category->parent_id);
        $this->data['data']             =   $brothers;
        return $this->respond($this->data);
    }

    public function brand($id){
        $brand                          =   Brand::findOrFail($id) ;
        $category                       =   GoodsCategory::findOrFail($brand->category_id);
        $categoryId                     =   $category->id;
        while($category->parent_id > 0){
            $category                   =   GoodsCategory::where('id',$category->parent_id)->first();
            $categoryId                 =   $category->id;
        }
        $this->data                     =   GoodsCategory::getAllCategory($categoryId);
        return $this->respond($this->data);
    }

    public function tree(){
        return $this->respond(GoodsCategory::getAllCategory());
    }

    protected function beforeShow(){
        //面包屑
        $crumb                                  =   [];
        $crumb[]                                =   [
            'title'                             =>  trans('html.index'),
            'url'                               =>  url('/'),
            'separator'                         =>  '>'
        ];
        $category                               =   GoodsCategory::find($this->model->parent_id);
        if($category){
            $array                                  =   [];
            $category                               =   GoodsCategory::find($category->parent_id);
            while ($category!=null){
                $array[]                            =   $category;
                $category                               =   GoodsCategory::find($category->parent_id);
            }
            if(count($array) > 0){
                for ($i=count($array);$i>0;$i--){
                    $crumb[]                        =   [
                        'title'                     =>  $array[$i-1]->name,
                        'url'                       =>  route('goodsCategory.show',['id'=>$array[$i-1]->id]),
                        'separator'                 =>  '>'
                    ];
                }
            }

        }
        $crumb[]                                =   [
            'title'                             =>  $this->model->name,
            'url'                               =>  route('goodsCategory.show',['id'=>$this->model->id]),
            'separator'                         =>  ''
        ];
        $this->data['crumb']                    =   $crumb;
        $this->data['title']                    =   $this->model->name;

        //筛选条件

        $sort                                   =   request()->input('sort','synthesized');
        $brand                                  =   request()->input('brand','');
        $attr                                   =   request()->input('attr','');
        $goods_where['category_id']             =   $this->model->id;

        if(!empty($brand ) ){
            $brandArray                         =   explode(':',$brand);
            $goods_where['brand_id']            =   $brandArray[0];
        }else{
            $brands                             =   Brand::getFilterByCategoryIds(GoodsCategory::getAllCategoryId($this->model->id));

        }
        $filter_goods_ids                       =   Goods::where($goods_where)->select("id")->get();

        $inputAttrs                             =   explode(',',$attr);
        $except                                 =   [];
        if($inputAttrs){
            foreach ($inputAttrs as $inputAttr){
                $inputAttr                      =   explode(':',$inputAttr);
                $except[]                       =   $inputAttr[0];
            }

        }
        $attrs                                  =   GoodsAttribute::getFilterByGoodsIds($filter_goods_ids,$except);


        $filters['attrs']                       =   $attrs ? :[];
        $filters['brands']                      =   isset($brands)? $brands :[];
        $this->data['filters']                  =   $filters;

        $goods                                  =   Goods::whereIn('id',$filter_goods_ids);
        if($sort=='synthesized') {
            $goods->orderBy('id','desc');
        }
        if($sort=='price'){
            $goods->orderBy('price','asc');
        }
        if($sort=='sales'){
            $goods->orderBy('sale_num','desc');
        }
        $list                                   =   $goods->paginate(config('restful.page_rows'));
        $list->appends(['sort' => $sort,'brand'=>$brand,'attr'=>$attr]);

        $params                                 =   [
            'sort'                              =>  $sort,
            'brand'                             =>  $brand,
            'attr'                              =>  $attr
        ];
        $this->data['params']                   =   $params;
        $this->data['list']                     =   $list;
    }

    /**
     * @throws ParamException
     */
    protected function beforeDelete(){
        $children                               =   GoodsCategory::getChildren($this->model->id);
        if(count($children)) {
            throw new ParamException(trans("html.common.must_delete_children"));
        }
    }
}