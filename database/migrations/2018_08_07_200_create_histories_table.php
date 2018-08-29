<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('passenger');
            $table->string('phone');
            $table->string('email');
            $table->string('info')->nullable();
            $table->string('pick_address');
            $table->string('drop_address');
            $table->string('distance');
            $table->string('elapsed_time');
            $table->string('order_date');
            $table->string('order_date_time');
            $table->string('driver_name')->nullable();
            $table->string('car_num')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('histories');
    }
}
