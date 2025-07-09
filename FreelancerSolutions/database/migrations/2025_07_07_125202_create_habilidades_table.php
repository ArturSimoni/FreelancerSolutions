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
        Schema::create('habilidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->timestamps();
        });

        Schema::create('perfil_freelancer_habilidade', function (Blueprint $table) {
            $table->foreignId('perfil_freelancer_id')->constrained()->onDelete('cascade');
            $table->foreignId('habilidade_id')->constrained()->onDelete('cascade');
            $table->primary(['perfil_freelancer_id', 'habilidade_id']);
        });

        Schema::create('projeto_categoria', function (Blueprint $table) {
            $table->foreignId('projeto_id')->constrained()->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->primary(['projeto_id', 'categoria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projeto_categoria');
        Schema::dropIfExists('perfil_freelancer_habilidade');
        Schema::dropIfExists('habilidades');
        Schema::dropIfExists('categorias');
    }
};
