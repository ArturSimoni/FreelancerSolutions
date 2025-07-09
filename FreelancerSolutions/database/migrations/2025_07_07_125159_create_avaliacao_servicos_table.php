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
            $table->foreignId('avaliador_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('avaliado_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('projeto_id')->constrained()->onDelete('cascade');
            $table->text('comentario')->nullable();
            $table->integer('nota');
            $table->string('tipo_avaliacao');
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
