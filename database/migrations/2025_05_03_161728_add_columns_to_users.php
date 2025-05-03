<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up()
    {
        // 1) Add as nullable so this ALTER won’t blow up
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('university_id')
                ->nullable()
                ->after('id');
            $table->unsignedBigInteger('department_id')
                ->nullable()
                ->after('university_id');
        });

        // 2) Back-fill existing rows with a valid tenant
        //    (Change the 1 to an existing university & department ID in your DB)
        DB::table('users')
            ->whereNull('university_id')
            ->update(['university_id' => 1]);
        DB::table('users')
            ->whereNull('department_id')
            ->update(['department_id' => 1]);

        // 3) Now add the foreign-key constraints
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->onDelete('cascade');

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');
        });
    }

        public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');

            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });
    }
};
