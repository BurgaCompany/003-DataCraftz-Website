<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id');
            $table->foreignId('id_driver');
            $table->foreignId('from_station_id');
            $table->foreignId('to_station_id');
            $table->double('min_price');
            $table->double('max_price');
            $table->double('price')->nullable();
            $table->time('time_start');
            $table->time('time_arrive');
            $table->timestamps();

            $table->foreign('bus_id')->references('id')->on('busses')->onDelete('cascade');
            $table->foreign('id_driver')->references('id')->on('driver_conductor_bus')->onDelete('cascade');
            $table->foreign('from_station_id')->references('id')->on('bus_stations')->onDelete('cascade');
            $table->foreign('to_station_id')->references('id')->on('bus_stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
