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
        Schema::table('users', function (Blueprint $table) {
            // add university_id FK
            $table->foreignId('university_id')
                ->after('id')
                ->constrained('universities')
                ->onDelete('cascade');

            // add department_id FK
            $table->foreignId('department_id')
                ->after('university_id')
                ->constrained('departments')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // drop in reverse order
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');

            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });
    }
};
