<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('attendance_status', ['Yes', 'No'])->default('No');
            $table->enum('fees_status', ['Yes', 'No'])->default('No');
            $table->enum('exam_status', ['Yes', 'No'])->default('No');
            $table->enum('graduation_status', ['Graduating', 'Not Graduating'])->default('Not Graduating');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('students');
    }
};
