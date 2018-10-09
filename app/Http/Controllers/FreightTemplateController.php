<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\FreightConfig;
use App\Models\FreightRegion;
use App\Models\FreightTemplate;
use App\Models\Goods;
use App\Models\Region;
use App\Services\FreightService;
use Illuminate\Support\Facades\Auth;
use Jcove\Restful\Restful;
use Illuminate\Support\Facades\Validator;

class FreightTemplateController extends Controller
{
    protected $model;
    use Restful;

    public function __construct()
    {
        $this->model                    =   new FreightTemplate();
        $this->setExceptField(['regions']);
    }

    protected function validator($data){
        $rules                          =   [
            'regions'           =>  'required|array',
            'name'              =>  'required',
        ];
        return Validator::make($data,$rules);
    }

    protected function prepareSave(){

    }
    protected function where(){
        return [];
    }
    protected function saved(){
        $this->saveRegions();
    }

    protected function updated(){
        $this->updatedRegions();
    }

    protected function saveRegions(){
        $regions                        =   request()->regions;
        $rule                           =   [
          //  'region_id'             =>  'exists:regions,id',
            'country_id'            =>  'required|exists:countries,id',
            'first_unit'        =>    'required',
            'first_money'       =>    'required',
            'continue_unit'     =>  'required',
            'continue_money'    =>  'required'
        ];
        foreach ($regions as $region){
            Validator::make($region,$rule)->validate();
        }
        foreach ($regions as $region){
            $region['template_id']      =   $this->model->id;
            $region['type']             =   $this->model->type;
            $region['region_id']        =   $region['region_id'] ? : 0;
            unset($region['delete']);
            FreightRegion::firstOrCreate($region);
        }
    }

    protected function updatedRegions(){
        $regions                        =   request()->regions;
        $rule                           =   [
          //  'region_id'             =>  'required|exists:regions,id',
            'country_id'            =>  'required|exists:countries,id',
            'first_unit'        =>    'required',
            'first_money'       =>    'required',
            'continue_unit'     =>  'required',
            'continue_money'    =>  'required'
        ];
        foreach ($regions as $region){
            Validator::make($region,$rule)->validate();
        }
        foreach ($regions as $region){
            $region['template_id']      =   $this->model->id;
            $region['region_id']        =   $region['region_id'] ? : 0;
            if(isset($region['delete']) && $region['delete'] == 1){
                FreightRegion::where('id',$region['id'])->delete();
            }elseif(isset($region['id']) && $region['id'] > 0){
                unset($region['delete']);
                $freightRegion          =   FreightRegion::find($region['id']);
                if($freightRegion){
                    $region['type']             =   $this->model->type;
                    foreach ($region as $key => $v){
                        $freightRegion->$key=$v;
                    }

                    $freightRegion->save();
                }

            }else{
                unset($region['delete']);
                FreightRegion::firstOrCreate($region);
            }

        }
    }

    protected function beforeShow(){
        $regions                        =   FreightRegion::where('template_id',$this->model->id)->get();
        $this->model->regions           =   $regions;
    }

    public function getShippingFee(){
        $goodsId                        =   request()->goods_id;
        $countryId                      =   request()->country_id;
        $regionId                       =   request()->region_id;
        $goods                          =   Goods::findOrFail($goodsId);
        $where['country_id']            =   $countryId;
        $where['template_id']           =   $goods->freight_template_id;
        if($regionId){
            $where['region_id']         =   $regionId;
        }
        $freight                        =   FreightRegion::where($where)->first();
        if(empty($freight)){
            $Region                     =   new Region();
            $parentIds                  =   $Region->getParentId($regionId);
            unset($where['region_id']);
            $freight                    =   FreightRegion::whereIn('region_id',$parentIds)->where($where)->orderBy('region_id','desc')->first();
        }
        if(empty($freight)){
            $freight                    =   FreightRegion::where(['country_id'=>$countryId,'template_id'=>$goods->freight_template_id,'region_id'=>0])->first();
        }
        if(!empty($freight)){
            $freightService             =   new FreightService();
            $freightService->setGoods($goods);
            $freightService->setFreight($freight);
            $freightService->setNum(1);
            $freightService->doCalculation();
            $fee                        =   $freightService->getFee();
            $this->data['fee']          =   $fee;
            return $this->respond($this->data);
        }else{
            return $this->respond(['message' =>trans('message.region_not_support'),'code'=>1],200);
        }
    }
    public function fee(){
        $freightService             =   new FreightService();
        $fee                        =   $freightService->getGoodsShippingFee(Cart::getUserCheckedCarts(Auth::id()),request()->address_id);
        if($fee){
            $this->data['fee']          =   $fee;
            return $this->respond($this->data);
        } else{
            return $this->respond(['message' =>trans('message.region_not_support'),'code'=>1],200);
        }
    }
}