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
        Schema::create('psychotest_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_code_id');
            $table->string('name');
            $table->string('email');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('education');
            $table->date('test_date');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('finish_time')->nullable();
            $table->string('session_token')->nullable(); // Token untuk identifikasi sesi browser
            $table->timestamps();
            $table->foreign('access_code_id')->references('id')->on('access_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychotest_participants');
    }
};
