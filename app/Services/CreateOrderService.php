<?php
namespace App\Services;
use App\Events\OrderCreated;
use App\Exceptions\CartException;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Jcove\Promotion\Facades\Promotion;

/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/17
 * Time: 15:50
 */

class CreateOrderService
{
    /**
     * @var array 购物车列表
     */
    private $carts;

    /**
     * @var double 商品总金额
     */
    private $goodsAmount                        =   0;

    /**
     * @var double 优惠后总金额
     */
    private $promotionAmount                    =   0;

    /**
     * @var double 实际应付
     */
    private $total                              =   0;

    /**
     * @var double 优惠金额
     */
    private $promotion                          =   0;

    /**
     * @var double 运费
     */
    private $shippingFee                        =   0;

    /**
     * @var array 促销
     */
    private $promotions;

    /**
     * @var array 订单商品
     */
    private $orderGoods;

    /**
     * @var \App\Models\UserAddress
     */
    private $address;

    /**
     * @var Order
     */
    private $order;

    /**
     * @return float
     */
    public function getShippingFee(): float
    {
        return $this->shippingFee;
    }

    /**
     * @param float $shippingFee
     */
    public function setShippingFee(float $shippingFee)
    {
        $this->shippingFee = $shippingFee;
    }

    /**
     * @param $carts
     * @throws CartException
     */
    public function setCarts(){
        $this->carts                                =   Cart::getUserCheckedCarts(Auth::id());
        if(null==$this->carts || count($this->carts)<=0){
            throw new CartException(trans('message.cart_is_empty'));
        }
    }


    public function getCarts()
    {
        return $this->carts;
    }

    public function setAddress($address){
        if(!($address instanceof UserAddress)){
            $address                                =   UserAddress::getByIdAndUserId($address);
        }
        $this->address                              =   $address;
    }

    public function getPromotions(){
        $products                                   =   [];
        foreach ($this->carts as $cart){
            $product['id']                           =   $cart->goods_id;
            $product['name']                         =   $cart->goods_name;
            $goods                                   =   $cart->goods;
            if($cart->goods_spec_item_id > 0){
                $specification                       =   $cart->specification;

                if($specification){
                    $product['price']                =   $specification->price;
                }else{
                    $goods                           =   $cart->goods;
                    $product['price']                =   $goods->price;
                }

            }else{
                if(null==$goods){
                    $goods                           =   $cart->goods;
                }
                $product['price']                    =   $goods->price;


            }
            $product['country_id']                   =   $goods->country_id;
            $product['num']                          =   $cart->num;
            $product['spec']                         =   $cart->goods_spec_item_id;
            $product['cover']                        =   $cart->cover;
            $products[]                              =   $product;

        }
        $promotions                                 =   Promotion::products($products);
        $this->promotions                           =   $promotions;
    }
    public function calculateFee(){
        if($this->promotions && count($this->promotions) > 0){
            foreach ($this->promotions as $promotion){
                $this->promotionAmount              +=  $promotion->getPromotionAmount();
                $this->goodsAmount                  +=   $promotion->getPromotionAmount();
                foreach ($promotion->getProducts() as $product){
                    $orderGoods                     =   new OrderGoods();
                    $orderGoods->goods_id           =   $product->id;
                    $orderGoods->goods_name         =   $product->name;
                    $orderGoods->num                =   $product->num;
                    $orderGoods->final_price        =   $product->final_price;
                    $orderGoods->goods_spec_item_id =   $product->spec ?: 0;
                    $orderGoods->cover              =   $product->cover;
                    $this->orderGoods[]             =   $orderGoods;
                }
            }
        }
        $this->total                                =   $this->goodsAmount+$this->shippingFee;
    }

    /**
     * @return Order
     * @throws CartException
     */
    public function create(){
        DB::transaction(function (){
            $this->order                             =   $this->createOrder();
            $this->createOrderGoods($this->order->id);
            $this->order->orderGoods                =   $this->orderGoods;
            Cart::clearChecked();
        });
        event(new OrderCreated($this->order));

        return $this->order;
    }
    public function createOrder(){
        $order                                      =   new \App\Models\Order();
        $order->order_sn                            =   $this->createOrderSn();
        $order->consignee                           =   $this->address->consignee;
        $order->country                             =   $this->address->country ? : '';
        $order->province                            =   $this->address->province;
        $order->country                             =   $this->address->consignee;
        $order->city                                =   $this->address->city;
        $order->district                            =   $this->address->district? : '';
        $order->town                                =   $this->address->town ? : '';
        $order->address                             =   $this->address->address;
        $order->phone                               =   $this->address->phone;
        $order->goods_amount                        =   $this->goodsAmount;
        $order->shipping_fee                        =   $this->shippingFee;
        $order->total_amount                        =   $this->total;
        $order->user_id                             =   Auth::id();
        $order->pay_code                            =   request()->pay_code ? :'';
        if($order->pay_code == 'mastCard' || $order->pay_code == 'visa'){
            $order->pay_code                        =   'paypal';
        }
        $order->save();
        return $order;
    }

    protected function createOrderGoods($orderId){
        foreach ($this->orderGoods as $goods){
            $goods->order_id                            =   $orderId;
            $goods->save();
        }
    }

    protected function createOrderSn(){
       return '10'.(new \Carbon\Carbon())->format('YmdHis').time();
    }
}