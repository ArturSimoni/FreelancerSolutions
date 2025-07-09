<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'perfil' => 'administrador',
            ]
        );

        User::firstOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Cliente Teste',
                'password' => Hash::make('password'),
                'perfil' => 'cliente',
            ]
        );

        User::firstOrCreate(
            ['email' => 'freelancer@example.com'],
            [
                'name' => 'Freelancer Teste',
                'password' => Hash::make('password'),
                'perfil' => 'freelancer',
            ]
        );


        $this->call(CategoriaSeeder::class);

        $this->call(HabilidadeSeeder::class);
    }
}
