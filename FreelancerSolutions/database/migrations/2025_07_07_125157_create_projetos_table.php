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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade'); // ID_Cliente
            $table->string('titulo');
            $table->text('descricao');
            $table->decimal('orcamento', 10, 2);
            $table->date('prazo');
            $table->string('status')->default('aberto'); // 'aberto', 'em_andamento', 'aguardando_aprovacao', 'concluido', 'cancelado'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
