<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/25
 * Time: 15:35
 */

namespace App\Http\Controllers\Admin;


use App\Models\GoodsAndCategory;
use App\Models\GoodsAttribute;
use Carbon\Carbon;
use Jcove\Restful\Restful;
use App\Exceptions\GoodsException;
use App\Http\Requests\CreateSpecificationParams;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\GoodsGallery;
use App\Models\GoodsModel;
use App\Models\GoodsModelSpecification;
use App\Models\GoodsModelSpecificationItem;
use App\Models\GoodsRelation;
use App\Models\GoodsSpecification;
use App\Models\GoodsSpecificationItemPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
        $this->setExceptField(['galleries','attrs','specifications','specification_items','relations','categories']);
    }
    protected function validator($data){
        return Validator::make($data,[
            'name'                          =>  'required',
            'store'                         =>  'required|numeric|min:1|max:999999',
            'model_id'                      =>  'required|numeric|exists:goods_models,id'
        ]);
    }
    protected function where()
    {
        $where                = [];

        if ($isHot = request()->is_hot) {
            $where['is_hot'] = $isHot;
        }
        if ($isRecommend = request()->is_recommend) {
            $where['is_recommend'] = $isRecommend;
        }
        if ($isNew = request()->is_new) {
            $where['is_new'] = $isNew;
        }
        if ($isSpecial = request()->is_special) {
            $where['is_special'] = $isSpecial;
        }
        if ($brand = request()->brand) {
            $brandArray = explode(':', $brand);
            $where['brand_id'] = $brandArray[0];
        }
        if ($brandId = request()->brand_id) {
            $where['brand_id'] = $brandId;
        }
        return $where;
    }
    public function index()
    {
        $categoryId = request()->input('category_id', 0);
        $keywords = request()->input('q', '');

        $goods_where = $this->where();
        $goods = new Goods();
        $categoryIds = GoodsCategory::getAllCategoryId($categoryId);
        if ($categoryId > 0) {
            $goods = $goods->whereIn('category_id', $categoryIds);
        }
        $filter_goods_ids = $goods->where($goods_where)->where('name', 'like', '%' . $keywords . '%')->select("id")->get();
        $list = Goods::list('id', 'in', $filter_goods_ids);

        $this->data = $list;


        return $this->respond($this->data);
    }
    protected function beforeShow()
    {

        //属性
        $attributes = GoodsAttribute::where('goods_id', $this->model->id)->get();
        //规格
        $specs = [];
        $specPrice = GoodsSpecificationItemPrice::where('goods_id', $this->model->id)->select(DB::raw('GROUP_CONCAT(`key` ORDER BY store desc SEPARATOR \'_\') as k'))->first();
        if ($specPrice) {
            $keys = $specPrice->k;
            $keys = str_replace('_', ',', $keys);

            $items = DB::table('goods_model_specifications as s')
                ->join('goods_model_specification_items as i', function ($join) {
                    $join->on('s.model_id', '=', 'i.model_id')
                        ->on('s.id', '=', 'i.specification_id');
                })
                ->whereIn('i.id', explode(',', $keys))
                ->select(DB::raw('s.name as name,i.name as value,s.id as id,i.id as item_id'))->get();
            $specNames = [];
            foreach ($items as $key => $val) {
                if (!key_exists($val->name, $specNames)) {

                    $specNames[$val->name] = $key;
                    $specs[$key] = [
                        'name' => $val->name,
                        'id' => $val->id

                    ];
                    $specs[$key]['items'][] = [
                        'id' => $val->item_id,
                        'value' => $val->value
                    ];
                } else {
                    $specs[$specNames[$val->name]]['items'][] = [
                        'id' => $val->item_id,
                        'value' => $val->value
                    ];
                }
            }
        }
        //规格对应价格
        $specPrice = [];
        $specPrice = GoodsSpecificationItemPrice::where('goods_id', $this->model->id)->get();
        $specificationPrices = [];
        if (count($specPrice) > 0) {
            foreach ($specPrice as $item) {
                $specificationPrices[$item->key] = [
                    'item_id' => $item->id,
                    'name' => $item->name,
                    'store' => $item->store,
                    'price' => $item->price,
                    'key' => $item->key
                ];
            }
        }
        $this->model->specification_prices = $specificationPrices ?: [];
        //相册
        $galleries = GoodsGallery::where('goods_id', $this->model->id)->get();
        $this->model->attrs = $attributes ? $attributes : [];
        $this->model->specifications = array_values($specs) ?: [];
        $this->model->galleries = $galleries ?: [];
        $this->model->brand = Brand::find($this->model->id);

        //分类
        $categories = GoodsAndCategory::where('goods_id',$this->model->id)->get();
        $this->model->categories                            =   $categories;


    }

    protected function prepareSave()
    {
        if(empty($this->model->getAttribute("goods_sn"))){
            $this->model->goods_sn = random_int(1000000000, 9999999999);
        }
        $categories                                 =   request()->categories;
        if(!empty($categories)) {
            $this->model->category_id               =   $categories[0];
        }

        $this->validateSpecification(request()->model_id, request()->specifications);
    }

    /**
     * @throws GoodsException
     */
    protected function saved()
    {
        $this->saveAttributes();
        $this->saveGalleries();
        $this->saveSpecifications();
        $this->saveSpecificationItems();
        $this->saveRelations();
        $this->saveCategories();

    }

    /**
     * @throws GoodsException
     */
    protected function updated()
    {
        $this->updateAttributes();
        $this->updateGalleries();
        $this->updateSpecifications();
        $this->updateSpecificationItems();
        $this->updateRelations();
        $this->updateCategories();
    }

    protected function saveGalleries()
    {
        $galleries = request()->galleries;
        if ($galleries) {
            $collection = new Collection();
            foreach ($galleries as $gallery) {
                $goodsGallery = new GoodsGallery();
                $goodsGallery->goods_id = $this->model->id;
                $goodsGallery->path = $gallery['path'];
                $goodsGallery->file_id = $gallery['file_id'];
                $goodsGallery->save();
                $collection->push($goodsGallery);
            }
            $this->model->galleries = $collection;
        }
    }

    protected function saveAttributes()
    {
        $attributes = request()->attrs;
        if ($attributes) {
            $collection = new Collection();
            foreach ($attributes as $attribute) {
                $goodsAttribute = new GoodsAttribute();
                $goodsAttribute->goods_id = $this->model->id;
                $goodsAttribute->attribute_id = $attribute['attribute_id'] ?: 0;
                $goodsAttribute->attribute_name = $attribute['attribute_name'] ?: '';
                $goodsAttribute->attribute_value = $attribute['attribute_value'] ?: '';
                $goodsAttribute->save();
                $collection->push($goodsAttribute);
            }
            $this->model->attributes = $collection;
        }
    }
    protected function saveCategories()
    {
        $categories = request()->categories;
        if ($categories) {
            $collection = new Collection();
            foreach ($categories as $categoryId) {
                $goodsAndCategory                       =   new GoodsAndCategory();
                $goodsAndCategory->goods_id             =   $this->model->id;
                $goodsAndCategory->category_id          =   $categoryId;
                $goodsAndCategory->save();
                $collection->push($goodsAndCategory);
            }
            $this->model->categories                    =   $collection;
        }
    }

    public function createSpecificationParams(CreateSpecificationParams $request)
    {
        $modelId = $request->model_id;
        $specifications = $request->specifications;
        return respond($this->createSpecificationItemsKeyAndName($modelId, $specifications));

    }

    protected function createSpecificationItemsKeyAndName($modelId, $specifications)
    {
        $specItems = [];
        foreach ($specifications as $specification) {
            $items = GoodsModelSpecificationItem::where(['specification_id' => $specification['id'], 'model_id' => $modelId])->whereIn('id', $specification['items'])->get();
            if (count($items)) {
                $specItems[] = $items;
            }

        }
        $arrayKeys = [];
        $arrayValues = [];

        if (count($specItems) > 0) {
            foreach ($specItems as $k => $v) {
                foreach ($v as $item) {
                    $arrayKeys[$k][] = $item->id;
                    $arrayValues[$k][] = $item->name;
                }
                ksort($arrayKeys[$k]);
            }

            $arrayKeys = cartesian($arrayKeys, '_');
            $arrayValues = cartesian($arrayValues, '_');
            $array = [];
            foreach ($arrayKeys as $key => $value) {
                $array[] = ['key' => $value, 'name' => $arrayValues[$key]];
            }
            return $array;
        }
        return [];

    }

    /**
     * @param $modelId
     * @param $specifications
     * @throws GoodsException
     */
    protected function validateSpecification($modelId, $specifications)
    {
        if (empty($modelId) && $this->isUpdate()) {
            return;
        }
        $this->goodsModel = GoodsModel::findOrFail($modelId);
        $this->goodsModelSpecifications = GoodsModelSpecification::where('model_id', $modelId)->get();
        $array = [];
        if ($this->goodsModelSpecifications && count($this->goodsModelSpecifications) > 0) {
            foreach ($this->goodsModelSpecifications as $specification) {
                $array[] = $specification->id;
            }
        }
        if ($specifications && count($specifications) > 0) {
            foreach ($specifications as $r) {
                if (!in_array($r['id'], $array)) {
                    throw new GoodsException(trans('message.goods_model_specification_not_exist'));
                }
            }
        }

    }

    protected function saveSpecifications()
    {
        $specifications = request()->specifications;
        if ($specifications) {
            $collection = new Collection();
            $values = [];
            foreach ($specifications as $specification) {
                $specificationIds[] = $specification['id'];

                //规格值
                $specificationItems = GoodsModelSpecificationItem::where('specification_id', $specification['id'])->get();
                $v = [];
                foreach ($specificationItems as $item) {
                    $v[] = $item->name;
                }
                $values[$specification['id']] = $v;

            }
            foreach ($specifications as $specification) {

                //验证
                $specification = GoodsModelSpecification::findOrFail($specification['id']);
                $goodsSpecification = new GoodsSpecification();
                $goodsSpecification->goods_id = $this->model->id;
                $goodsSpecification->specification_id = $specification->id;
                $goodsSpecification->specification_name = $specification->name;
                $goodsSpecification->specification_value = $values[$specification->id];
                $goodsSpecification->save();
                $collection->push($goodsSpecification);
            }
            $this->model->specifications = $collection;
        }
    }

    protected function saveSpecificationItems()
    {
        $specifications = request()->specifications;
        $modelId = request()->model_id;
        $store = request()->store;
        $specificationItems = request()->specification_items;
        if ($specificationItems) {
            //规格验证
            $inputKeys = [];
            $inputStores = [];
            $inputPrices = [];
            $totalStore = 0;
            foreach ($specificationItems as $specificationsItem) {
                $validator = Validator::make($specificationsItem, [
                    'key' => 'required',
                    'store' => 'required|numeric|min:1|max:999999',
                    'price' => 'required|numeric'
                ]);
                $validator->validate();
                $inputKeys[] = $specificationsItem['key'];
                $inputStores[] = $specificationsItem['store'];
                $totalStore += $specificationsItem['store'];
                $inputPrices[] = $specificationsItem['price'];
            }
            $itemKeyAndNames = $this->createSpecificationItemsKeyAndName($modelId, $specifications);
            if (count($itemKeyAndNames) > 0) {
                $keys = [];
                foreach ($itemKeyAndNames as $itemKeyAndName) {
                    $keys[] = $itemKeyAndName['key'];
                }

                foreach ($inputKeys as $inputKey) {
                    if (!in_array($inputKey, $keys)) {
                        throw new GoodsException(trans('message.spec_item_key_error'));
                    }
                }
            }

            //验证库存
            if ($totalStore != $store) {
                throw new GoodsException(trans('message.spec_store_not_match_store'));
            }
            //保存
            $collection = new Collection();
            foreach ($inputKeys as $key => $value) {
                $goodsSpecificationPrice = new GoodsSpecificationItemPrice();
                $goodsSpecificationPrice->key = $value;
                $goodsSpecificationPrice->name = $itemKeyAndNames[$key]['name'];
                $goodsSpecificationPrice->store = $inputStores[$key];
                $goodsSpecificationPrice->goods_id = $this->model->id;
                $goodsSpecificationPrice->price = $inputPrices[$key];
                $goodsSpecificationPrice->save();
                $collection->push($goodsSpecificationPrice);
            }
            $this->model->specification_items = $collection;
        }
    }

    protected function saveRelations()
    {
        $relations = request()->relations;
        if ($relations && count($relations) > 0) {
            $collection = new Collection();
            foreach ($relations as $goodsId) {
                $goods = Goods::info($goodsId);
                if ($goods) {
                    $relation = new GoodsRelation();
                    $relation->goods_id = $this->model->id;
                    $relation->relation_goods_id = $goods->id;
                    $relation->goods_name = $goods->name;
                    $relation->price = $goods->price;
                    $relation->cover = $goods->cover;
                    $relation->save();
                    $collection->push($relation);
                }
            }
            $this->model->relations = $collection;
        }
    }

    protected function updateRelations()
    {
        $relations = request()->relations ?: [];

        $oldRelations = GoodsRelation::where('goods_id', $this->model->id)->get();
        $oldRelationGoodsIds = [];
        $adds = [];
        $deletes = [];
        if ($oldRelations) {

            foreach ($oldRelations as $oldRelation) {
                $oldRelationGoodsIds[] = $oldRelation->relation_goods_id;
            }
        }
        if ($relations && count($relations) > 0) {
            $adds = array_diff($relations, $oldRelationGoodsIds);
        }
        if (count($oldRelationGoodsIds) > 0) {
            $deletes = array_diff($oldRelationGoodsIds, $relations);
        }
        $collection = new Collection();
        if (isset($adds) && $adds && count($adds) > 0) {
            foreach ($adds as $add) {
                $goods = Goods::info($add);
                if ($goods) {
                    $goodsRelation = new GoodsRelation();
                    $goodsRelation->goods_id = $this->model->id;
                    $goodsRelation->relation_goods_id = $goods->id;
                    $goodsRelation->goods_name = $goods->name;
                    $goodsRelation->price = $goods->price;
                    $goodsRelation->cover = $goods->cover;
                    $goodsRelation->save();
                    $collection->push($goodsRelation);
                }
            }
        }


        if (isset($deletes) && $deletes && count($deletes) > 0) {
            GoodsRelation::whereIn('relation_goods_id', $deletes)->where('goods_id', $this->model->id)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldRelations as $key => $value) {
                    if ($value->relation_goods_id == $delete) {
                        $oldRelations->forget($key);
                    }
                }
            }
        }
        $this->model->relations = $collection->intersect($oldRelations);
    }

    protected function updateCategories()
    {

        $categories                         = request()->categories;
        if (empty($categories)) {
            return;
        }
        $oldCategories                      = GoodsAndCategory::where('goods_id', $this->model->id)->get();
        $oldCategoryIds                     = [];
        $categoryIds                        = [];
        $adds                               = [];
        $deletes                            = [];
        if ($oldCategories) {
            foreach ($oldCategories as $oldCategory) {
                $oldCategoryIds[] = $oldCategory->category_id;
            }
        }

        if ($categories) {
            foreach ($categories as $category) {
                $categoryIds[] = $category;
            }
        }


        if (count($categoryIds) > 0) {
            $adds = array_diff($categoryIds, $oldCategoryIds);
        }
        if (count($oldCategoryIds) > 0) {
            $deletes = array_diff($oldCategoryIds, $categoryIds);
        }

        $collection = new Collection();
        foreach ($adds as $add) {

            foreach ($categories as $category) {
                if ($category == $add) {
                    $goodsAndCategory = new GoodsAndCategory();
                    $goodsAndCategory->goods_id = $this->model->id;
                    $goodsAndCategory->category_id = $category;
                    $goodsAndCategory->save();
                    $collection->push($goodsAndCategory);
                }
            }
            $this->model->categories = $collection;
        }

        if (count($deletes) > 0) {
            GoodsAndCategory::whereIn('category_id', $deletes)->where('goods_id',$this->model->id)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldCategories as $key => $value) {
                    if ($value->category_id == $delete) {
                        $oldCategories->forget($key);
                    }
                }
            }
        }
        $this->model->categories = $collection->intersect($oldCategories);
    }
    protected function updateAttributes()
    {

        $attributes = request()->attrs;
        if (empty($attributes)) {
            return;
        }
        $oldAttributes = GoodsAttribute::where('goods_id', $this->model->id)->get();
        $oldAttributeIds = [];
        $attributeIds = [];
        $adds = [];
        $deletes = [];
        if ($oldAttributes) {
            foreach ($oldAttributes as $oldAttribute) {
                $oldAttributeIds[] = $oldAttribute->attribute_id;
            }
        }

        if ($attributes) {
            foreach ($attributes as $attribute) {
                $attributeIds[] = $attribute['attribute_id'];
            }
        }


        if (count($attributeIds) > 0) {
            $adds = array_diff($attributeIds, $oldAttributeIds);
        }
        if (count($oldAttributeIds) > 0) {
            $deletes = array_diff($oldAttributeIds, $attributeIds);
        }

        $collection = new Collection();
        foreach ($adds as $add) {

            foreach ($attributes as $attribute) {
                if ($attribute['attribute_id'] == $add) {
                    $goodsAttribute = new GoodsAttribute();
                    $goodsAttribute->goods_id = $this->model->id;
                    $goodsAttribute->attribute_id = $attribute['attribute_id'];
                    $goodsAttribute->attribute_name = $attribute['attribute_name'] ?: '';
                    $goodsAttribute->attribute_value = $attribute['attribute_value'] ?: '';
                    $goodsAttribute->save();
                    $collection->push($goodsAttribute);
                }
            }
            $this->model->attrs = $collection;
        }

        if (count($deletes) > 0) {
            GoodsAttribute::whereIn('attribute_id', $deletes)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldAttributes as $key => $value) {
                    if ($value->attribute_id == $delete) {
                        $oldAttributes->forget($key);
                    }
                }
            }
        }
        $this->model->attrs = $collection->intersect($oldAttributes);
    }

    public function updateGalleries()
    {

        $galleries = request()->galleries;
        if (empty($galleries)) {
            return;
        }
        $oldGalleries = GoodsGallery::where('goods_id', $this->model->id)->get();
        $oldGalleryIds = [];
        $fileIds = [];
        $adds = [];
        $deletes = [];
        if ($oldGalleries) {
            foreach ($oldGalleries as $oldGallery) {
                $oldGalleryIds[] = $oldGallery->file_id;
            }
        }

        if ($galleries) {
            foreach ($galleries as $gallery) {
                $fileIds[] = $gallery['file_id'];
            }
        }


        if (count($fileIds) > 0) {
            $adds = array_diff($fileIds, $oldGalleryIds);
        }
        if (count($oldGalleryIds) > 0) {
            $deletes = array_diff($oldGalleryIds, $fileIds);
        }

        $collection = new Collection();
        foreach ($adds as $add) {

            foreach ($galleries as $gallery) {
                if ($gallery['file_id'] == $add) {
                    $goodsGallery = new GoodsGallery();
                    $goodsGallery->goods_id = $this->model->id;
                    $goodsGallery->file_id = $gallery['file_id'];
                    $goodsGallery->path = $gallery['path'];
                    $goodsGallery->save();
                    $collection->push($goodsGallery);
                }
            }
        }

        if (count($deletes) > 0) {
            GoodsGallery::whereIn('file_id', $deletes)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldGalleries as $key => $value) {
                    if ($value->file_id == $delete) {
                        $oldGalleries->forget($key);
                    }
                }
            }
        }
        $this->model->galleries = $collection->intersect($oldGalleries);
    }

    public function updateSpecifications()
    {


        $specifications = request()->specifications;
        if (empty($specifications)) {
            return;
        }
        $oldSpecifications = GoodsSpecification::where('goods_id', $this->model->id)->get();
        $oldSpecificationIds = [];
        $specificationIds = [];
        $adds = [];
        $deletes = [];
        if ($oldSpecifications) {
            foreach ($oldSpecifications as $oldSpecification) {
                $oldSpecificationIds[] = $oldSpecification->specification_id;
            }
        }
        $values = [];
        if ($specifications) {
            foreach ($specifications as $specification) {
                $specificationIds[] = $specification['id'];

                //去掉不存在的规格值
                $specificationItems = GoodsModelSpecificationItem::where('specification_id', $specification['id'])->get();
                $v = [];
                foreach ($specificationItems as $item) {
                    $v[] = $item->name;
                }

                $values[$specification['id']] = $v;
            }
        }


        if (count($specificationIds) > 0) {
            $adds = array_diff($specificationIds, $oldSpecificationIds);
        }
        if (count($oldSpecificationIds) > 0) {
            $deletes = array_diff($oldSpecificationIds, $specificationIds);
        }

        $collection = new Collection();
        foreach ($adds as $add) {


            $specification = GoodsModelSpecification::findOrFail($add);
            if ($specification->id == $add) {
                $goodsSpecification = new GoodsSpecification();
                $goodsSpecification->goods_id = $this->model->id;
                $goodsSpecification->specification_id = $specification->id;
                $goodsSpecification->specification_name = $specification->name;
                $goodsSpecification->specification_value = $values[$specification->id];
                $goodsSpecification->save();
                $collection->push($goodsSpecification);
            }

        }

        if (count($deletes) > 0) {
            GoodsSpecification::whereIn('specification_id', $deletes)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldSpecifications as $key => $value) {
                    if ($value->id == $delete) {
                        $oldSpecifications->forget($key);
                    }
                }
            }
        }
        $this->model->galleries = $collection->intersect($oldSpecifications);
    }

    public function updateSpecificationItems()
    {

        $specPrices = request()->specification_items;
        if (empty($specPrices)) {
            return;
        }
        $specifications = request()->specifications;
        $modelId = request()->model_id;
        $store = request()->store;
        $oldSpecPrices = GoodsSpecificationItemPrice::where('goods_id', $this->model->id)->get();
        $oldKeys = [];
        $inputKeys = [];
        $inputStores = [];
        $inputPrices = [];
        $totalStore = 0;
        $adds = [];
        $saves = [];
        $deletes = [];
        if ($oldSpecPrices) {
            foreach ($oldSpecPrices as $oldSpecPrice) {
                $oldKeys[] = $oldSpecPrice->key;
            }
        }
        $keys = [];
        if ($specPrices) {
            //规格验证

            foreach ($specPrices as $specificationsItem) {
                $validator = Validator::make($specificationsItem, [
                    'key' => 'required',
                    'store' => 'required|numeric|min:1|max:999999',
                    'price' => 'required|numeric'
                ]);
                $validator->validate();
                $inputKeys[] = $specificationsItem['key'];
                $inputStores[] = $specificationsItem['store'];
                $totalStore += $specificationsItem['store'];
                $inputPrices[] = $specificationsItem['key'];
            }
            $itemKeyAndNames = $this->createSpecificationItemsKeyAndName($modelId, $specifications);
            if (count($itemKeyAndNames) > 0) {
                foreach ($itemKeyAndNames as $itemKeyAndName) {
                    $keys[] = $itemKeyAndName['key'];
                }

                foreach ($inputKeys as $inputKey) {
                    if (!in_array($inputKey, $keys)) {
                        throw new GoodsException(trans('message.spec_item_key_error'));
                    }
                }
            }

            //验证库存
            if ($totalStore != $store) {
                throw new GoodsException(trans('message.spec_store_not_match_store'));
            }

        }


        if (count($inputKeys) > 0) {
            $adds = array_diff($inputKeys, $oldKeys);
        }
        if (count($oldKeys) > 0) {
            $deletes = array_diff($oldKeys, $inputKeys);
        }
        $saves              =   array_intersect($inputKeys,$oldKeys);


        $collection = new Collection();
        $itemKeyAndNameArray = $this->arrayToKeyValue($itemKeyAndNames);
        foreach ($adds as $add) {

            foreach ($specPrices as $specPrice) {
                if ($specPrice['key'] == $add) {
                    $goodsSpecificationPrice = new GoodsSpecificationItemPrice();
                    $goodsSpecificationPrice->key = $specPrice['key'];
                    $goodsSpecificationPrice->name = $itemKeyAndNameArray[$add];
                    $goodsSpecificationPrice->store = $specPrice['store'];
                    $goodsSpecificationPrice->goods_id = $this->model->id;
                    $goodsSpecificationPrice->price = $specPrice['price'];
                    $goodsSpecificationPrice->save();
                    $collection->push($goodsSpecificationPrice);
                }
            }
        }
        foreach ($saves as $save) {

            foreach ($specPrices as $specPrice) {
                if ($specPrice['key'] == $save) {
                    $goodsSpecificationPrice = GoodsSpecificationItemPrice::find($specPrice['item_id']);
                    $goodsSpecificationPrice->key = $specPrice['key'];
                    $goodsSpecificationPrice->name = $itemKeyAndNameArray[$save];
                    $goodsSpecificationPrice->store = $specPrice['store'];
                    $goodsSpecificationPrice->goods_id = $this->model->id;
                    $goodsSpecificationPrice->price = $specPrice['price'];
                    $goodsSpecificationPrice->save();
                    $collection->push($goodsSpecificationPrice);
                }
            }
        }

        if (count($deletes) > 0) {
            GoodsSpecificationItemPrice::whereIn('key', $deletes)->delete();
            foreach ($deletes as $delete) {
                foreach ($oldSpecPrices as $key => $value) {
                    if ($value->key == $delete) {
                        $oldSpecPrices->forget($key);
                    }
                }
            }
        }
        $this->model->galleries = $collection->intersect($oldSpecPrices);
    }

    protected function arrayToKeyValue($array)
    {
        if ($array && count($array)) {
            $a = [];
            foreach ($array as $item) {
                $a[$item['key']] = $item['name'];
            }
            return $a;
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel(){
        $spreadSheet                        =   new Spreadsheet();
        $workSheet                          =   $spreadSheet->getActiveSheet();
        $workSheet->setTitle('商品');
        $workSheet->setCellValueByColumnAndRow(1,1,'商品代码');
        $workSheet->setCellValueByColumnAndRow(2,1,'规格代码');
        $workSheet->setCellValueByColumnAndRow(3,1,'规格名称');
        $workSheet->setCellValueByColumnAndRow(4,1,'商品名称');
        $workSheet->setCellValueByColumnAndRow(5,1,'商品简称');
        $workSheet->setCellValueByColumnAndRow(6,1,'重量');
        $workSheet->setCellValueByColumnAndRow(7,1,'体积');
        $workSheet->setCellValueByColumnAndRow(8,1,'打包积分');
        $workSheet->setCellValueByColumnAndRow(9,1,'销售积分');
        $workSheet->setCellValueByColumnAndRow(10,1,'标准进价');
        $workSheet->setCellValueByColumnAndRow(11,1,'标准售价');
        $workSheet->setCellValueByColumnAndRow(12,1,'代理售价');
        $workSheet->setCellValueByColumnAndRow(13,1,'成本价');
        $workSheet->setCellValueByColumnAndRow(14,1,'类别');
        $workSheet->setCellValueByColumnAndRow(15,1,'供应商');
        $workSheet->setCellValueByColumnAndRow(16,1,'单位');
        $workSheet->setCellValueByColumnAndRow(17,1,'库存状态');
        $workSheet->setCellValueByColumnAndRow(18,1,'备注');
        $workSheet->setCellValueByColumnAndRow(19,1,'商品税号');
        $workSheet->setCellValueByColumnAndRow(20,1,'税率');
        $workSheet->setCellValueByColumnAndRow(21,1,'原产地');
        $workSheet->setCellValueByColumnAndRow(22,1,'供应商货号');
        $workSheet->setCellValueByColumnAndRow(23,1,'保质期');
        $workSheet->setCellValueByColumnAndRow(24,1,'预警天数');
        $workSheet->setCellValueByColumnAndRow(25,1,'生产日期');
        $workSheet->setCellValueByColumnAndRow(26,1,'图片地址');
        $workSheet->setCellValueByColumnAndRow(27,1,'品牌');
        $workSheet->setCellValueByColumnAndRow(28,1,'税收分类编码');
        $workSheet->setCellValueByColumnAndRow(29,1,'税收优惠政策内容');

        $categoryId = request()->input('category_id', 0);
        $keywords = request()->input('q', '');

        $goods_where = $this->where();
        $goods = new Goods();
        $categoryIds = GoodsCategory::getAllCategoryId($categoryId);
        if ($categoryId > 0) {
            $goods = $goods->whereIn('category_id', $categoryIds);
        }
        $filter_goods_ids = $goods->where($goods_where)->where('name', 'like', '%' . $keywords . '%')->select("id")->get();
        $list = Goods::whereIn('id',$filter_goods_ids)->get();
        if($list && ($length = count($list)) > 0){
            $j                              =   2;
            for ($i=0; $i < $length; $i++ ){
                $row                        =   $list->offsetGet($i);
                if(count($specs = $row->specification_item_price) > 0){
                    foreach ($specs as $spec){
                        $workSheet->setCellValueByColumnAndRow(1,$j,$row->goods_sn);
                        $workSheet->setCellValueByColumnAndRow(2,$j,$spec->id);
                        $workSheet->setCellValueByColumnAndRow(3,$j,$spec->name);
                        $workSheet->setCellValueByColumnAndRow(4,$j,$row->name);
                        $workSheet->setCellValueByColumnAndRow(5,$j,$row->name);
                        $workSheet->setCellValueByColumnAndRow(6,$j,$row->weight);
                        $workSheet->setCellValueByColumnAndRow(7,$j,'');
                        $workSheet->setCellValueByColumnAndRow(8,$j,0);
                        $workSheet->setCellValueByColumnAndRow(9,$j,0);
                        $workSheet->setCellValueByColumnAndRow(10,$j,'');
                        $workSheet->setCellValueByColumnAndRow(11,$j,$row->price);
                        $workSheet->setCellValueByColumnAndRow(12,$j,$row->price);
                        $workSheet->setCellValueByColumnAndRow(13,$j,$row->price);
                        $workSheet->setCellValueByColumnAndRow(14,$j,$row->category ? $row->category->name : '');
                        $workSheet->setCellValueByColumnAndRow(15,$j,'');
                        $workSheet->setCellValueByColumnAndRow(16,$j,$row->unit);
                        $workSheet->setCellValueByColumnAndRow(17,$j,$row->store? '正常': '异常');
                        $workSheet->setCellValueByColumnAndRow(18,$j,'');
                        $workSheet->setCellValueByColumnAndRow(19,$j,'');
                        $workSheet->setCellValueByColumnAndRow(20,$j,'');
                        $workSheet->setCellValueByColumnAndRow(21,$j,'');
                        $workSheet->setCellValueByColumnAndRow(22,$j,'');
                        $workSheet->setCellValueByColumnAndRow(23,$j,'');
                        $workSheet->setCellValueByColumnAndRow(24,$j,30);
                        $workSheet->setCellValueByColumnAndRow(25,$j,'');
                        $workSheet->setCellValueByColumnAndRow(26,$j,$row->cover);
                        $workSheet->setCellValueByColumnAndRow(27,$j,$row->brand? $row->brand->name : '');
                        $workSheet->setCellValueByColumnAndRow(28,$j,'');
                        $workSheet->setCellValueByColumnAndRow(29,$j,'');
                        $j++;
                    }
                }else{
                    $workSheet->setCellValueByColumnAndRow(1,$j,$row->goods_sn);
                    $workSheet->setCellValueByColumnAndRow(2,$j,'');
                    $workSheet->setCellValueByColumnAndRow(3,$j,'');
                    $workSheet->setCellValueByColumnAndRow(4,$j,$row->name);
                    $workSheet->setCellValueByColumnAndRow(5,$j,$row->name);
                    $workSheet->setCellValueByColumnAndRow(6,$j,$row->weight);
                    $workSheet->setCellValueByColumnAndRow(7,$j,'');
                    $workSheet->setCellValueByColumnAndRow(8,$j,0);
                    $workSheet->setCellValueByColumnAndRow(9,$j,0);
                    $workSheet->setCellValueByColumnAndRow(10,$j,'');
                    $workSheet->setCellValueByColumnAndRow(11,$j,$row->price);
                    $workSheet->setCellValueByColumnAndRow(12,$j,$row->price);
                    $workSheet->setCellValueByColumnAndRow(13,$j,$row->price);
                    $workSheet->setCellValueByColumnAndRow(14,$j,$row->category ? $row->category->name : '');
                    $workSheet->setCellValueByColumnAndRow(15,$j,'');
                    $workSheet->setCellValueByColumnAndRow(16,$j,$row->unit);
                    $workSheet->setCellValueByColumnAndRow(17,$j,$row->store? '正常': '异常');
                    $workSheet->setCellValueByColumnAndRow(18,$j,'');
                    $workSheet->setCellValueByColumnAndRow(19,$j,'');
                    $workSheet->setCellValueByColumnAndRow(20,$j,'');
                    $workSheet->setCellValueByColumnAndRow(21,$j,'');
                    $workSheet->setCellValueByColumnAndRow(22,$j,'');
                    $workSheet->setCellValueByColumnAndRow(23,$j,'');
                    $workSheet->setCellValueByColumnAndRow(24,$j,30);
                    $workSheet->setCellValueByColumnAndRow(25,$j,'');
                    $workSheet->setCellValueByColumnAndRow(26,$j,$row->cover);
                    $workSheet->setCellValueByColumnAndRow(27,$j,$row->brand? $row->brand->name : '');
                    $workSheet->setCellValueByColumnAndRow(28,$j,'');
                    $workSheet->setCellValueByColumnAndRow(29,$j,'');
                    $j++;
                }

            }
        }

        $filename                           =   '商品'. (new Carbon())->format('Y-m-d-H-i-s').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }

}