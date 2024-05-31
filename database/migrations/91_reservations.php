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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('bus_id');
            $table->foreignId('schedule_id');
            $table->integer('tickets_booked');
            $table->date('date_departure');
            $table->enum('status', [1, 2]);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bus_id')->references('id')->on('busses');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
