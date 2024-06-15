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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('address')->nullable(false);
            $table->enum('gender', ['male', 'female'])->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('images')->nullable(true);
            $table->double('rating')->nullable(true);
            $table->integer('balance')->nullable(true);
            $table->unsignedBigInteger('id_upt')->nullable();
            $table->unsignedBigInteger('id_po')->nullable();
            $table->timestamps();
            $table->foreign('id_upt')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
};
