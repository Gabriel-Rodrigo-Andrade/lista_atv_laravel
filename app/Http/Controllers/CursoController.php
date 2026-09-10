<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function index(): View
    {
        $cursos = Curso::withCount('alunos')->orderBy('nome')->get();

        return view('cursos.index', compact('cursos'));
    }

    public function show(Curso $curso): View
    {
        $curso->load(['alunos' => fn ($consulta) => $consulta->orderBy('nome')]);

        return view('cursos.show', compact('curso'));
    }
}
