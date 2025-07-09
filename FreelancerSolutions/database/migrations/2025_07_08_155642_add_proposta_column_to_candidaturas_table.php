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
            Schema::table('candidaturas', function (Blueprint $table) {
                $table->text('proposta')->after('freelancer_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('candidaturas', function (Blueprint $table) {
                $table->dropColumn('proposta');
            });
        }
    };
