<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSpecificationItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_specification_item_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('goods_id')->default(0);
            $table->string('key')->default('');
            $table->string('name')->default('');
            $table->decimal('price')->default(0);
            $table->integer('store')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_specification_prices');
    }
}
