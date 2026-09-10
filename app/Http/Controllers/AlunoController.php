<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AlunoController extends Controller
{
    public function index(): View { return view('alunos.index', ['alunos' => []]); }
    public function create(): View { return view('alunos.create'); }
    public function store(Request $request): string { return 'Salvar aluno'; }
    public function show(string $aluno): View { return view('alunos.show'); }
    public function edit(string $aluno): View { return view('alunos.edit'); }
    public function update(Request $request, string $aluno): string { return 'Atualizar aluno: '.$aluno; }
    public function destroy(string $aluno): string { return 'Excluir aluno: '.$aluno; }
}
