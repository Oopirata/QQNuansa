<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->foreignId('users_id')->constrained()->onDelete('cascade');
            $table->string('hari');
            $table->dateTime('jam_mulai');
            $table->dateTime('jam_selesai');
            $table->dateTime('tanggal');
            $table->string('deskripsi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};