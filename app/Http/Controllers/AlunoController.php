<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function index(): string { return 'Lista de alunos'; }
    public function create(): string { return 'Cadastrar aluno'; }
    public function store(Request $request): string { return 'Salvar aluno'; }
    public function show(string $aluno): string { return 'Aluno: '.$aluno; }
    public function edit(string $aluno): string { return 'Editar aluno: '.$aluno; }
    public function update(Request $request, string $aluno): string { return 'Atualizar aluno: '.$aluno; }
    public function destroy(string $aluno): string { return 'Excluir aluno: '.$aluno; }
}
