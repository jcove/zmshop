<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('goods_sn')->unique()->default('');
            $table->string('cover')->default('');
            $table->decimal('price',10,2)->default(0);
            $table->decimal('market_price',10,2)->default(0);
            $table->string('unit')->default('');
            $table->integer('store')->default(0);
            $table->integer('sale_num')->default(0);
            $table->integer('is_hot')->default(0);
            $table->integer('is_new')->default(0);
            $table->integer('is_recommend')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('view')->default(0);
            $table->integer('model_id')->default(0);
            $table->integer('merchant_id')->default(0);
            $table->text('content');
            $table->text('instruction');
            $table->integer('brand_id')->default(0);
            $table->tinyInteger('rx')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('weight')->default(0);
            $table->tinyInteger('is_special')->default(0);
            $table->integer('freight_template_id')->default(0);
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
        Schema::table('goods', function (Blueprint $table) {
            //
        });
    }
}
