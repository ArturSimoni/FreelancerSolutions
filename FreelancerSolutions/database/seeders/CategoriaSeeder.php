<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('categorias')->truncate();

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

        DB::table('categorias')->insert($categorias);

        Schema::enableForeignKeyConstraints();
    }
}

