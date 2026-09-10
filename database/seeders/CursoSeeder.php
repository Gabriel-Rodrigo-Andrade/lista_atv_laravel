<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Informática', 'Administração', 'Enfermagem'] as $nome) {
            Curso::firstOrCreate(['nome' => $nome]);
        }
    }
}
