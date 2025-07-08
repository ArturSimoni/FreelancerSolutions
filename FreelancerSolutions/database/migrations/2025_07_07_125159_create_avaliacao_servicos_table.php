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
        Schema::create('avaliacao_servicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliador_id')->constrained('users')->onDelete('cascade'); // ID_avaliador (Cliente ou Freelancer)
            $table->foreignId('avaliado_id')->constrained('users')->onDelete('cascade'); // ID_avaliado (Freelancer ou Cliente)
            $table->foreignId('projeto_id')->constrained()->onDelete('cascade'); // ID_projeto
            $table->text('comentario')->nullable();
            $table->integer('nota'); // Nota (1-5, por exemplo)
            $table->string('tipo_avaliacao'); // 'cliente_para_freelancer' ou 'freelancer_para_cliente'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_servicos');
    }
};
