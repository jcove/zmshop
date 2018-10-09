<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->default(0);
            $table->string('goods_name')->default('');
            $table->string('cover')->default('');
            $table->decimal('price')->default(0);
            $table->string('goods_spec_item_name')->default('');
            $table->integer('num')->default(1);
            $table->tinyInteger('delivery_id')->default(0);
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
        Schema::dropIfExists('deliveriy_goods');
    }
}
