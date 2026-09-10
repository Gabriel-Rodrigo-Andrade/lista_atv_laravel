<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\User;
use App\Http\Requests\AlunoRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlunoController extends Controller
{
    public function index(Request $request): View
    {
        $consulta = Aluno::with('curso');
        if ($request->filled('curso')) {
            $consulta->whereHas('curso', function ($consultaCurso) use ($request) {
                $consultaCurso->where('nome', $request->input('curso'));
            });
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
    public function create(): View
    {
        $cursos = Curso::orderBy('nome')->get();
        $professores = User::orderBy('name')->get();
        return view('alunos.create', compact('cursos', 'professores'));
    }

    public function store(AlunoRequest $request): \Illuminate\Http\RedirectResponse
    {
        $dados = $request->validated();
        Aluno::create($dados);
        return redirect()->route('alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso.');
    }

    public function show(Aluno $aluno): View
    {
        return view('alunos.show', compact('aluno'));
    }

    public function edit(Aluno $aluno): View
    {
        $cursos = Curso::orderBy('nome')->get();
        $professores = User::orderBy('name')->get();
        return view('alunos.edit', compact('aluno', 'cursos', 'professores'));
    }

    public function update(AlunoRequest $request, Aluno $aluno): \Illuminate\Http\RedirectResponse
    {
        $dados = $request->validated();
        $aluno->update($dados);
        return redirect()->route('alunos.index')->with('sucesso', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno): \Illuminate\Http\RedirectResponse
    {
        $aluno->delete();
        return redirect()->route('alunos.index')->with('sucesso', 'Aluno excluído com sucesso.');
    }
}
