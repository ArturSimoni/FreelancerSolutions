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
        Schema::disableForeignKeyConstraints();

        DB::table('habilidades')->truncate();

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

        DB::table('habilidades')->insert($habilidades);

        Schema::enableForeignKeyConstraints();
    }
}
