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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->integer('raw_score')->nullable();
            $table->integer('iq')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('participant_id'); // ID peserta
            $table->string('pdf_path')->nullable(); // Path ke file PDF
            $table->timestamps();

            $table->foreign('participant_id')->references('id')->on('psychotest_participants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
