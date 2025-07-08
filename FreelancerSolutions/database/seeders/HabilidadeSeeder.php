<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HabilidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desativa a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // Limpa a tabela antes de inserir novos dados
        DB::table('habilidades')->truncate();

        // Dados de exemplo para as habilidades
        $habilidades = [
            ['nome' => 'PHP'],
            ['nome' => 'Laravel'],
            ['nome' => 'JavaScript'],
            ['nome' => 'React'],
            ['nome' => 'Vue.js'],
            ['nome' => 'HTML'],
            ['nome' => 'CSS'],
            ['nome' => 'Tailwind CSS'],
            ['nome' => 'MySQL'],
            ['nome' => 'PostgreSQL'],
            ['nome' => 'UI/UX Design'],
            ['nome' => 'Figma'],
            ['nome' => 'Adobe Photoshop'],
            ['nome' => 'Adobe Illustrator'],
            ['nome' => 'Copywriting'],
            ['nome' => 'SEO'],
            ['nome' => 'Marketing de Conteúdo'],
            ['nome' => 'Gestão de Projetos'],
            ['nome' => 'Comunicação'],
            ['nome' => 'Edição de Vídeo'],
            ['nome' => 'Animação'],
            ['nome' => 'Consultoria Estratégica'],
            ['nome' => 'Análise de Dados'],
        ];

        // Insere os dados na tabela 'habilidades'
        DB::table('habilidades')->insert($habilidades);

        // Reativa a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();
    }
}
