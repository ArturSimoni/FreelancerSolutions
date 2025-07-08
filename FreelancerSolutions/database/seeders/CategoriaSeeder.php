<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Importe a fachada DB
use Illuminate\Support\Facades\Schema; // Importe a fachada Schema

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativa a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // Limpa a tabela antes de inserir novos dados
        // Agora que as chaves estrangeiras estão desativadas, o TRUNCATE funcionará
        DB::table('categorias')->truncate();

        // Dados de exemplo para as categorias
        $categorias = [
            ['nome' => 'Desenvolvimento Web', 'descricao' => 'Criação de websites, aplicações web, front-end e back-end.'],
            ['nome' => 'Design Gráfico', 'descricao' => 'Criação de logotipos, branding, ilustrações, design de UI/UX.'],
            ['nome' => 'Escrita e Tradução', 'descricao' => 'Criação de conteúdo, artigos, blogs, tradução de idiomas.'],
            ['nome' => 'Marketing Digital', 'descricao' => 'SEO, SEM, mídias sociais, email marketing, publicidade online.'],
            ['nome' => 'Suporte Administrativo', 'descricao' => 'Assistência virtual, entrada de dados, gestão de projetos.'],
            ['nome' => 'Vídeo e Animação', 'descricao' => 'Edição de vídeo, motion graphics, animações 2D/3D.'],
            ['nome' => 'Consultoria', 'descricao' => 'Consultoria em negócios, estratégia, TI, finanças.'],
            ['nome' => 'Fotografia', 'descricao' => 'Edição de fotos, fotografia de produtos, eventos.'],
        ];

        // Insere os dados na tabela 'categorias'
        DB::table('categorias')->insert($categorias);

        // Reativa a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();
    }
}

