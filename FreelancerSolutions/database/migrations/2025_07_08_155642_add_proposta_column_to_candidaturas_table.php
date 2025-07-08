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
                // Adiciona a coluna 'proposta' como TEXT.
                // Se você quer que seja obrigatória, remova ->nullable().
                // Adicione-a após 'freelancer_id' para manter a ordem lógica.
                $table->text('proposta')->after('freelancer_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('candidaturas', function (Blueprint $table) {
                // Remove a coluna 'proposta' se a migração for revertida
                $table->dropColumn('proposta');
            });
        }
    };
