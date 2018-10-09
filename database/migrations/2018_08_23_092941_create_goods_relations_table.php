<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->default(0);
            $table->integer('relation_goods_id')->default(0);
            $table->string('goods_name');
            $table->string('cover')->default('');
            $table->decimal('price',10,2)->default(0);
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
        Schema::dropIfExists('goods_relations');
    }
}
