<?php

namespace Database\Seeders;

use App\Models\Aluno;
use Illuminate\Database\Seeder;

class AlunoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = \App\Models\Curso::pluck('id');
        Aluno::factory()->count(10)->sequence(fn ($sequence) => ['curso_id' => $cursos[$sequence->index % $cursos->count()]])->create();
    }
}
