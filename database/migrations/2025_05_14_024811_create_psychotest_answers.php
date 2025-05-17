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
        Schema::create('psychotest_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('question_id');
            $table->text('answer');
            $table->timestamps();
            $table->foreign('participant_id')->references('id')->on('psychotest_participants')->onDelete('cascade');
            // Kita akan menghubungkan ini dengan tabel questions yang mungkin sudah ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychotest_answers');
    }
};
