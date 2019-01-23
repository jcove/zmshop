<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('consignee')->default('');
            $table->string('country')->default('');
            $table->string('province')->default('');
            $table->string('city')->default('');
            $table->string('district')->default('');
            $table->string('address')->default('');
            $table->string('phone')->default('');
            $table->tinyInteger('is_default')->default(0);
            $table->string('zip_code')->default('');
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
        Schema::dropIfExists('user_addresses');
    }
}
