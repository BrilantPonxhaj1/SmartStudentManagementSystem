<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentFkToProfessorProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('professor_profiles', function (Blueprint $table) {
            // add the foreign-key constraint now that departments table exists
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('professor_profiles', function (Blueprint $table) {
            // drop the foreign-key constraint
            $table->dropForeign(['department_id']);
        });
    }
}
