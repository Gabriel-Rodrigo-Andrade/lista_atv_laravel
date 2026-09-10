<?php

namespace Database\Seeders;

use App\Models\Aluno;
use Illuminate\Database\Seeder;

class AlunoSeeder extends Seeder
{
    public function run(): void
    {
        Aluno::factory()->count(10)->create();
    }
}
