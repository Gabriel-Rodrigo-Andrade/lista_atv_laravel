<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoRequest;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AlunoController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Aluno::class);
        $request->validate([
            'nome' => ['nullable', 'string', 'max:255'],
            'curso' => ['nullable', 'string', 'max:255'],
            'recentes' => ['nullable', 'boolean'],
        ]);
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
        Gate::authorize('create', Aluno::class);
        $cursos = Curso::orderBy('nome')->get();
        $professores = User::where('role', 'professor')->orderBy('name')->get();

        return view('alunos.create', compact('cursos', 'professores'));
    }

    public function store(AlunoRequest $request): RedirectResponse
    {
        Gate::authorize('create', Aluno::class);
        $dados = $request->validated();
        Aluno::create($dados);

        return redirect()->route('alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso.');
    }

    public function show(Aluno $aluno): View
    {
        Gate::authorize('view', $aluno);

        return view('alunos.show', compact('aluno'));
    }

    public function edit(Aluno $aluno): View
    {
        Gate::authorize('update', $aluno);
        $cursos = Curso::orderBy('nome')->get();
        $professores = User::where('role', 'professor')->orderBy('name')->get();

        return view('alunos.edit', compact('aluno', 'cursos', 'professores'));
    }

    public function update(AlunoRequest $request, Aluno $aluno): RedirectResponse
    {
        Gate::authorize('update', $aluno);
        $dados = $request->validated();
        $aluno->update($dados);

        return redirect()->route('alunos.index')->with('sucesso', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno): RedirectResponse
    {
        Gate::authorize('delete', $aluno);
        $aluno->delete();

        return redirect()->route('alunos.index')->with('sucesso', 'Aluno excluído com sucesso.');
    }
}
