<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id');
            $table->integer('attribute_id');//模型属性id，对应goods_models_attribute下的id
            $table->string('attribute_name');//模型属性名称
            $table->string('attribute_value');//模型属性值
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
        //
    }
}
