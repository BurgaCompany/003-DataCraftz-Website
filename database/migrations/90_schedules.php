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
            $table->foreignId('from_station_id');
            $table->foreignId('to_station_id');
            $table->double('price');
            $table->time('time_start');
            $table->string('pwt');
            $table->timestamps();

            $table->foreign('bus_id')->references('id')->on('busses')->onDelete('cascade');
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
