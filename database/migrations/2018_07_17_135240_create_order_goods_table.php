<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->default(0);
            $table->integer('goods_id')->default(0);
            $table->string('name')->default('');
            $table->string('cover')->default('');
            $table->decimal('price')->default(0);
            $table->decimal('final_price')->default(0);
            $table->integer('goods_spec_item_id')->default(0);
            $table->string('goods_spec_item_name')->default('');
            $table->integer('num')->default(1);
            $table->tinyInteger('is_comment')->default(0);
            $table->tinyInteger('is_shipping')->default(0);
            $table->tinyInteger('delivery_id')->default(0);
            $table->integer('country')->default(0);
            $table->tinyInteger('is_return')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_goods');
    }
}
