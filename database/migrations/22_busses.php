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
        Schema::create('busses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('license_plate_number', 13); 
            $table->integer('chair')->unsigned(); 
            $table->enum('class', ['Ekonomi', 'Patas']);
            $table->enum('status', ['Belum Berangkat', 'Berangkat', 'Terkendala', 'Selesai'])->default('Belum Berangkat');
            $table->string('information')->nullable();
            $table->string('images')->nullable();
            $table->unsignedBigInteger('id_po')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('id_po')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('busses');
    }
};
