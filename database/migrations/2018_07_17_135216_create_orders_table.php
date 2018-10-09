<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('order_sn')->default('');
            $table->tinyInteger('order_status')->default(0);
            $table->tinyInteger('pay_status')->default(0);
            $table->tinyInteger('shipping_status')->default(0);
            $table->string('consignee')->default('');
            $table->string('country')->default('');
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->string('district')->default('');
            $table->string('town')->default('');
            $table->string('address')->default('');
            $table->string('phone')->default('');
            $table->string('express_code')->default('');
            $table->string('pay_code')->default('');
            $table->string('express_name')->default('');
            $table->decimal('goods_amount',10,2)->default(0);
            $table->decimal('shipping_fee',10,2)->default(0);
            $table->decimal('total_amount',10,2)->default(0);
            $table->dateTime('pay_time')->nullable();
            $table->dateTime('shipping_time')->nullable();
            $table->dateTime('confirm_time')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
