<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('student_number')->unique();
            $table->string('program');
            $table->integer('year_of_study');
            $table->integer('enrollment_year');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_profiles');
    }
}
