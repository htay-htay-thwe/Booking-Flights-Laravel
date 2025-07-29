<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->integer('flight_id');
            $table->integer('cart_id')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email');
            $table->string('country');
            $table->string('country_code');
            $table->string('phone_no');
            $table->string('passenger_first_name');
            $table->string('passenger_last_name');
            $table->string('gender');
            $table->date('birthday');
            $table->string('nationality');
            $table->string('class');
            $table->string('classPrice')->nullable();
            $table->string('kg')->nullable();
            $table->integer('kgPrice')->nullable();
            $table->string('seat')->nullable();
            $table->integer('seatPrice')->nullable();
            $table->string('insurance');
            $table->integer('insurancePrice')->nullable();
            $table->string('currency');
            $table->integer('total');
            $table->string('checkInStatus')->default('pending');
            $table->string('bookStatus')->default('pending'); // or nullable if needed
            $table->string('paymentStatus')->default('pending');
            $table->boolean('save')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
