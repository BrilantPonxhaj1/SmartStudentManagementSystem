    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSemestersTable extends Migration
    {
        public function up()
        {
            Schema::create('semesters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('university_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->date('registration_deadline')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('semesters');
        }
    }
