<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('return_sn');
            $table->integer('goods_id');
            $table->string('reason')->default('');
            $table->tinyInteger('refund_status')->default(0);
            $table->decimal('goods_amount',10,2)->default(0);
            $table->decimal('refund_money',10,2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->string('express_code',20)->default('');
            $table->string('express_sn',60)->default('');
            $table->string('express_name')->default('');
            $table->string('seller_remark')->default('');
            $table->integer('goods_spec_item_id')->default(0);
            $table->string('goods_spec_item_name')->default('');
            $table->integer('user_id')->default(0);
            $table->string('cover')->default('');
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
        Schema::dropIfExists('return_goods');
    }
}
