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
