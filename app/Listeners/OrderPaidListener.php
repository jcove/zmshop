<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Goods;
use App\Models\GoodsSpecificationItemPrice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class OrderPaidListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        if(config('shop.store_reduce',0) == 2){
            $order                          =   $event->getOrder();
            $orderGoods                     =   $order->order_goods;
            DB::transaction(function () use ($orderGoods){
                foreach ($orderGoods as $row){
                    $goods                      =   Goods::find($row->goods_id);
                    if($goods){
                        $store                  =   $goods->store - $row->num;
                        if($store < 0){
                            $store              =   0;
                        }
                        $goods->store           =   $store;
                        $goods->save();
                    }
                    if($row->goods_spec_item_id > 0){
                        $GoodsSpec              =   GoodsSpecificationItemPrice::find($row->goods_spec_item_id);
                        if($GoodsSpec){
                            $store              =   $GoodsSpec->store - $row->num;
                            if($store < 0){
                                $store          =   0;
                            }
                            $GoodsSpec->store   =   $store;
                            $GoodsSpec->save();
                        }
                    }

                }
            });


        }
    }
}
