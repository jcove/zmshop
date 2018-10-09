<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->default(0);
            $table->integer('config_id')->default(0);
            $table->integer('region_id')->default(0);
            $table->integer('country_id')->default(0);
            $table->double('first_unit')->default(1);
            $table->double('first_money')->default(0);
            $table->double('continue_unit')->default(0);
            $table->double('continue_money')->default(0);
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('freight_regions');
    }
}
