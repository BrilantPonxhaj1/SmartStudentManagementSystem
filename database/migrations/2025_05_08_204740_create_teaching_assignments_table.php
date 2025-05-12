<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_offering_id')
                ->constrained('course_offerings')
                ->cascadeOnDelete();
            $t->foreignId('professor_profile_id')
                ->constrained('professor_profiles')
                ->cascadeOnDelete();

            $t->enum('role', ['lead','ta','guest'])->default('lead');
            $t->unsignedTinyInteger('hours_per_week')->default(0);
            $t->string('office_hours')->nullable();

            $t->timestamps();

            $t->unique(
                ['course_offering_id','professor_profile_id'],
                'teaching_assignment_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_assignments');
    }
};
