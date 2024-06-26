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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('student_name');
            $table->date('date_of_birth');
            $table->string('father_occupation');
            $table->string('father_name');
            $table->string('father_education');
           $table->string('mother_name');
           $table->string('mother_education');
            $table->string('mother_occupation');
            $table->string('address');
            $table->string('father_cnic');
            $table->string('student_cnic');
            $table->string('applying_for_class');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
