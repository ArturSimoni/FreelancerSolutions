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
        Schema::create('candidaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade'); // ID_Freelancer
            $table->foreignId('projeto_id')->constrained()->onDelete('cascade'); // ID_Projeto
            $table->decimal('proposta_valor', 10, 2); // PropostaValor
            $table->integer('proposta_prazo'); // PropostaPrazo
            $table->string('status')->default('pendente'); // 'pendente', 'aceita', 'recusada'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidaturas');
    }
};
