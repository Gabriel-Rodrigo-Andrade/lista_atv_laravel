<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlunoController extends Controller
{
    public function index(Request $request): View
    {
        $consulta = Aluno::query();
        if ($request->filled('curso')) {
            $consulta->where('curso', $request->input('curso'));
        }
        if ($request->filled('nome')) {
            $consulta->where('nome', 'like', '%'.$request->input('nome').'%');
        }
        if ($request->boolean('recentes')) {
            $consulta->where('created_at', '>=', now()->subDays(7));
        }
        $alunos = $consulta->orderBy('nome')->get();
        $quantidade = Aluno::count();
        return view('alunos.index', compact('alunos', 'quantidade'));
    }
    public function create(): View { return view('alunos.create'); }
    public function store(Request $request): string { return 'Salvar aluno'; }
    public function show(string $aluno): View { return view('alunos.show'); }
    public function edit(string $aluno): View { return view('alunos.edit'); }
    public function update(Request $request, string $aluno): string { return 'Atualizar aluno: '.$aluno; }
    public function destroy(string $aluno): string { return 'Excluir aluno: '.$aluno; }
}
