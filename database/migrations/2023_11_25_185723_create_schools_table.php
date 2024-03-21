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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('contact_number');
            $table->boolean('is_approved')->default(false);
            $table->decimal('admission_fee', 10, 2);
            $table->enum('admission_status', ['Open', 'Closed'])->default('open');
            $table->decimal('tuition_fee', 10, 2);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('city');
            $table->string('area');
            $table->enum('board', ['Aga Khan', 'Cambridge','Federal','Matric'])->default('aga khan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
