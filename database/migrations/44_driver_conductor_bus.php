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
        Schema::create('driver_conductor_bus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->foreignId('bus_conductor_id');
            $table->foreignId('bus_id');


            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bus_conductor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bus_id')->references('id')->on('busses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_conductor_bus');
    }
};
