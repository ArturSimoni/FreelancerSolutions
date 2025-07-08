<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importe Hash para criar senhas
use App\Models\User; // Importe o modelo User

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // As linhas abaixo geralmente causam o erro de "Duplicate entry"
        // se você as executa múltiplas vezes sem limpar a tabela de usuários.
        // Comente-as se você já tem usuários ou se quer evitar duplicatas ao rodar db:seed.

        // \App\Models\User::factory(10)->create(); // Comentado para evitar duplicatas

        // \App\Models\User::factory()->create([ // Comentado para evitar duplicatas
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password'), // Certifique-se de que a senha é hashed
        //     // 'perfil' => 'cliente', // Adicione o perfil se o factory não o fizer
        // ]);

        // Se você quiser criar um usuário administrador padrão ao rodar os seeders,
        // e ter certeza de que ele não duplica, você pode usar findOrCreate ou truncate a tabela de usuários.
        // Para simplicidade e evitar o erro de duplicata, vamos criar um usuário se ele não existir,
        // ou você pode usar o migrate:fresh --seed para limpar tudo antes.

        // Exemplo: Criar um usuário administrador se ele não existir
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'perfil' => 'administrador', // Certifique-se de que o perfil está definido
            ]
        );

        // Exemplo: Criar um usuário cliente se ele não existir
        User::firstOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Cliente Teste',
                'password' => Hash::make('password'),
                'perfil' => 'cliente',
            ]
        );

        // Exemplo: Criar um usuário freelancer se ele não existir
        User::firstOrCreate(
            ['email' => 'freelancer@example.com'],
            [
                'name' => 'Freelancer Teste',
                'password' => Hash::make('password'),
                'perfil' => 'freelancer',
            ]
        );


        // Chama o CategoriaSeeder
        $this->call(CategoriaSeeder::class);

        // --- NOVO: Chama o HabilidadeSeeder ---
        $this->call(HabilidadeSeeder::class);
        // --- FIM DO NOVO ---
    }
}
