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
            $table->string('license_plate_number', 13); // maksimal 13 karakter
            $table->integer('chair')->unsigned(); // maksimal 2 kursi
            $table->enum('class', ['ekonomi', 'bisnis']); // pilihan ekonomi atau bisnis
            $table->enum('status', [1, 2, 3, 4, 5])->default(1); // Default status adalah 1 (Belum Berangkat)
            $table->string('information')->nullable(); // Field untuk keterangan tambahan jika status adalah 4
            $table->string('images')->nullable();
            $table->unsignedBigInteger('id_po')->nullable();
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
