<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/9/27
 * Time: 15:30
 */

namespace App\Services;


use App\Error;
use App\Exceptions\RegionNotSupportException;
use App\Models\Country;
use App\Models\FreightRegion;
use App\Models\Goods;
use App\Models\Region;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class FreightService
{
    private $goods;
    private $num;
    private $freight;
    private $fee;

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }



    /**
     * @return mixed
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * @param mixed $goods
     */
    public function setGoods($goods)
    {
        $this->goods = $goods;
    }

    /**
     * @return mixed
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param mixed $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }



    /**
     * @param mixed $freight
     */
    public function setFreight($freight)
    {
        $this->freight = $freight;
    }


    public function doCalculation(){
        switch ($this->freight->type) {
            case 1:
                //按重量
                $total_unit = $this->goods->weight * $this->num;//总重量
                break;
            default:
                //按件数
                $total_unit = $this->num;
                break;
        }
        $this->fee                      = $this->getFreightPrice($total_unit);
    }

    private function getFreightPrice($total_unit){
        $total_unit = floatval($total_unit);
        if($total_unit > $this->freight->first_unit){
            $average = ceil(($total_unit-$this->freight->first_unit) / $this->freight->continue_unit);
            $freight_price = $this->freight->first_money + $this->freight->continue_money * $average;
        }else{
            $freight_price = $this->freight->first_money;
        }
        return $freight_price;
    }

    /**
     * @param $goodsArray
     * @param $addressId
     * @return int|mixed
     * @throws RegionNotSupportException
     */
    public function getGoodsShippingFee($goodsArray,$addressId){
        $goodsIds                       =   $goodsArray->pluck('goods_id');
        $goodsList                      =   Goods::whereIn('id',$goodsIds)->get();

        $address                        =   UserAddress::findOrFail($addressId);
        $r                              =   Region::getByName($address->city);
        $c                              =   Country::getByName($address->country);

        $fee                            =   0;
        foreach ($goodsList as $goods){

            $freight                        =   $this->getFreight($goods->freight_template_id,$c->id,$r? $r->id: null);
            if(empty($freight)){
                throw  new RegionNotSupportException($goods->name.trans('message.region_not_support'),Error::region_not_support);
            }
            foreach ($goodsArray as  $item){
                if($goods->id == $item->goods_id){
                    $this->setNum($item->num);
                    $this->setFreight($freight);
                    $this->setGoods($goods);
                    $this->doCalculation();
                    $fee +=$this->getFee();
                }
            }
        }
        return$fee;
    }

    public function getFreight($templateId,$countryId,$regionId){
        $where['country_id']            =   $countryId;
        $where['template_id']           =   $templateId;
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
            $freight                    =   FreightRegion::where(['country_id'=>$countryId,'template_id'=>$templateId,'region_id'=>0])->first();
        }
        $this->freight                  =   $freight;
        return $freight;
    }
}