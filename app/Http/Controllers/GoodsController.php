<?php

namespace App\Http\Controllers;

use App\Exceptions\GoodsException;
use App\Http\Requests\CreateSpecificationParams;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsAndCategory;
use App\Models\GoodsAttribute;
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
use Jcove\Restful\Restful;

class GoodsController extends Controller
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
        $this->model = new Goods();
        $this->setExceptField(['galleries', 'attrs', 'specifications', 'specification_items', 'relations']);
    }

    protected function validator($data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'store' => 'required|numeric|min:1|max:999999',
            'model_id' => 'required|numeric|exists:goods_models,id'
        ]);
    }

    protected function where()
    {
        $where['status']                = 1;

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

        //面包屑
        $crumb = [];
        $crumb[] = [
            'title' => trans('html.index'),
            'url' => '/',
            'separator' => '>'
        ];
        $category = GoodsCategory::find($this->model->category_id);
        if ($category) {
            $goodsCate = $category;
            $array = [];
            $category = GoodsCategory::find($category->parent_id);
            while ($category != null) {
                $array[] = $category;
                $category = GoodsCategory::find($category->parent_id);
            }
            if (count($array) > 0) {
                for ($i = count($array) - 1; $i >= 0; $i--) {
                    $crumb[] = [
                        'title' => $array[$i]->name,
                        'url' => route('goods.index', ['category_id' => $array[$i]->id]),
                        'separator' => '>'
                    ];
                }
            }
            $crumb[] = [
                'title' => $goodsCate->name,
                'url' => route('goods.index', ['category_id' => $goodsCate->id]),
                'separator' => '>'
            ];
        }

        $crumb[] = [
            'title' => $this->model->name,
            'url' => route('goods.show', ['id' => $this->model->id]),
            'separator' => ''
        ];
        $this->data['crumb'] = $crumb;

    }

    protected function prepareSave()
    {
        $this->model->goods_sn = random_int(10000, 99999);
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


    public function index()
    {
        $categoryId = request()->input('category_id', 0);
        $keywords = request()->input('q', '');

        //筛选条件

        $sort = request()->input('sort', 'synthesized');
        $brand = request()->input('brand', '');

        $attr = request()->input('attr', '');
        $goods_where = $this->where();
        $goods = new Goods();
        $categoryIds = GoodsCategory::getAllCategoryId($categoryId);
        if ($categoryId > 0) {
            $goodsIds                               =   GoodsAndCategory::whereIn('category_id',$categoryIds)->select("goods_id")->get();
            $goods = $goods->whereIn('id', $goodsIds);
        }



        $filter_goods_ids = $goods->where($goods_where)->where('name', 'like', '%' . $keywords . '%')->select("id")->get();
        if (empty($brand)) {
            $brandIds                           =   Goods::getBrandsByIds($filter_goods_ids);
            $brands = Brand::whereIn('id',$brandIds)->get();
        }
        $inputAttrs = explode(',', $attr);
        $except = [];
        if ($inputAttrs) {
            foreach ($inputAttrs as $inputAttr) {
                $inputAttr = explode(':', $inputAttr);
                $except[] = $inputAttr[0];
            }

        }
        $attrs = GoodsAttribute::getFilterByGoodsIds($filter_goods_ids, $except);


        $filters['attrs'] = $attrs ?: [];
        $filters['brands'] = isset($brands) ? $brands : [];
        $this->data['filters'] = $filters;
        $list = Goods::list('id', 'in', $filter_goods_ids);
        $list->appends(['sort' => $sort, 'brand' => $brand, 'attr' => $attr,'category_id'=>$categoryId]);

        $params = [
            'sort' => $sort,
            'brand' => $brand,
            'attr' => $attr,
            'category_id' => $categoryId,
            'q'            =>$keywords
        ];
        $this->data['params'] = $params;

        if (!$this->canJson()) {
            //面包屑
            $crumb = [];
            $crumb[] = [
                'title' => trans('html.index'),
                'url' => url('/'),
                'separator' => '>'
            ];


            $category = GoodsCategory::find($categoryId);
            if ($category) {
                $array = [];
                $category = GoodsCategory::find($category->parent_id);
                while ($category != null) {
                    $array[] = $category;
                    $category = GoodsCategory::find($category->parent_id);
                }
                if (count($array) > 0) {
                    for ($i = count($array); $i > 0; $i--) {
                        $crumb[] = [
                            'title' => $array[$i - 1]->name,
                            'url' => route('goods.index', ['category_id' => $array[$i - 1]->id]),
                            'separator' => '>'
                        ];
                    }
                }

            }
            if ($keywords) {
                $crumb[] = [
                    'title' => $keywords,
                    'url' => '',
                    'separator' => ''
                ];
            }

            $this->data['crumb'] = $crumb;
            $this->setTitle($keywords||'');
            $this->data['list'] = $list;
        } else {
            $this->data = $list;
        }

        return $this->respond($this->data);
    }

    public function relation($id)
    {
        $list = GoodsRelation::where('goods_id', $id)->get();
        $this->data['data'] = $list;
        return $this->respond($this->data);
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

    public function specifications($id)
    {
        $specs = [];
        $keyQuery = GoodsSpecificationItemPrice::where('goods_id', $id)->select(DB::raw('GROUP_CONCAT(`key` ORDER BY store desc SEPARATOR \'_\') as k'))->first();
        if ($keyQuery) {
            $keys = $keyQuery->k;
            $keys = str_replace('_', ',', $keys);

            $items = DB::table('goods_model_specifications as s')
                ->join('goods_model_specification_items as i', function ($join) {
                    $join->on('s.model_id', '=', 'i.model_id')
                        ->on('s.id', '=', 'i.specification_id');
                })
                ->whereIn('i.id', explode(',', $keys))
                ->select(DB::raw('s.name as name,i.name as value,i.id as id'))->get();
            $specNames = [];
            foreach ($items as $key => $val) {
                if (!key_exists($val->name, $specNames)) {

                    $specNames[$val->name] = $key;
                    $specs[$key] = [
                        'name' => $val->name,
                    ];
                    $specs[$key]['items'][] = [
                        'id' => $val->id,
                        'value' => $val->value
                    ];
                } else {
                    $specs[$specNames[$val->name]]['items'][] = [
                        'id' => $val->id,
                        'value' => $val->value
                    ];
                }
            }
        }
        //规格对应价格

        $specPrices = GoodsSpecificationItemPrice::where('goods_id', $id)->get();
        $this->data['specification_prices'] = $specPrices ?: [];
        $this->data['specifications'] = $specs ? array_values($specs) : [];

        return $this->respond($this->data);
    }

    public function checkRx()
    {
        $ids = request()->id;
        $ids = explode(',', $ids);
        $count = $this->model->whereIn('id', $ids)->where('rx', 1)->count();
        if ($count > 0) {
            return $this->success();
        } else {
            return $this->fail(trans('message.data_not_found'), 404);
        }
    }
}