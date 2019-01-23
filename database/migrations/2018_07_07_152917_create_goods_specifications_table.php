<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->default(0);
            $table->integer('specification_id')->default(0);
            $table->string('specification_name')->default('');
            $table->string('specification_value')->default('');
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
        Schema::dropIfExists('goods_specifications');
    }
}
