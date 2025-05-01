<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessorProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('professor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->string('specialization');
            $table->enum('academic_role', ['professor','associate_professor','dean','vice_dean']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('professor_profiles');
    }
}
