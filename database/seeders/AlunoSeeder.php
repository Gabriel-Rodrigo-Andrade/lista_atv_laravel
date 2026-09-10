<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlunoSeeder extends Seeder
{
    public function run(): void
    {
        $cursos = Curso::pluck('id');
        Aluno::factory()->count(10)->sequence(fn ($sequence) => ['curso_id' => $cursos[$sequence->index % $cursos->count()], 'user_id' => User::where('role', 'professor')->value('id')])->create();
    }
}
