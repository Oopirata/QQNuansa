<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_vacancy_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('degree');
            $table->decimal('ipk', 3, 2);
            $table->string('nomor_hp');
            $table->string('province');
            $table->string('city');
            $table->integer('expected_salary');
            $table->string('applicant_email');
            $table->string('linkedin')->nullable();
            $table->string('cv_path');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }
};