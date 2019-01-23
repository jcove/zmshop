<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('goods_id')->default(0);
            $table->tinyInteger('is_check')->default(1);
            $table->integer('goods_spec_id')->default(0);
            $table->integer('num')->default(0);
            $table->string('cover')->default('');
            $table->tinyInteger('status')->default(1);
            $table->decimal('price',10,2)->default(0);
            $table->string('goods_spec_item_name')->default('');
            $table->string('goods_name')->default('');
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
        Schema::dropIfExists('carts');
    }
}
