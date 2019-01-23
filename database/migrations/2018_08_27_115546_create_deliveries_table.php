<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('delivery_sn')->default('');
            $table->string('consignee')->default('');
            $table->string('country')->default('');
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->string('district')->default('');
            $table->string('town')->default('');
            $table->string('address')->default('');
            $table->string('phone')->default('');
            $table->string('express_code')->default('');
            $table->string('express_name')->default('');
            $table->string('express_sn')->default('');
            $table->decimal('total_amount',10,2)->default(0);
            $table->decimal('goods_amount',10,2)->default(0);
            $table->decimal('shipping_fee',10,2)->default(0);
            $table->integer('user_id')->default(0);
            $table->tinyInteger('is_confirm')->default(0);
            $table->string('zip_code')->default('');
            $table->integer('depot')->default(0);
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
        Schema::dropIfExists('deliveries');
    }
}
