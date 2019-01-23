<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->default(0);
            $table->string('nick')->default('');
            $table->text('content');
            $table->tinyInteger('status')->default(1);
            $table->integer('user_id')->default(0);
            $table->text('images');
            $table->tinyInteger('express_rank')->default(5);
            $table->tinyInteger('goods_rank')->default(5);
            $table->tinyInteger('service_rank')->default(5);
            $table->integer('order_id')->default(0);
            $table->tinyInteger('is_anonymous')->default(0);
            $table->string('avatar')->default('');
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
        Schema::dropIfExists('comments');
    }
}
