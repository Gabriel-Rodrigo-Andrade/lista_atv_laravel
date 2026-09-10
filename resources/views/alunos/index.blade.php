@extends('layouts.app')
@section('title', 'Alunos')
@section('content')
<h1>Alunos</h1>
<p>Total de alunos: {{ $quantidade }}</p>
<form method="GET" action="{{ route('alunos.index') }}">
    <label for="nome">Nome contém</label><input id="nome" name="nome" value="{{ request('nome') }}">
    <label for="curso">Curso</label><input id="curso" name="curso" value="{{ request('curso') }}">
    <label><input type="checkbox" name="recentes" value="1" @checked(request()->boolean('recentes'))> Últimos 7 dias</label>
    <button>Filtrar</button><a href="{{ route('alunos.index') }}">Limpar</a>
</form>
@if (count($alunos) === 0)
    <p>Nenhum aluno cadastrado.</p>
@else
    <ul>
        @foreach ($alunos as $aluno)
            <li>
                <a href="{{ route('alunos.show', $aluno) }}">{{ $aluno->nome }}</a> — {{ $aluno->curso }}
                <a href="{{ route('alunos.edit', $aluno) }}">Editar</a>
                <form method="POST" action="{{ route('alunos.destroy', $aluno) }}">
                    @csrf
                    @method('DELETE')
                    <button>Excluir</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif
@endsection
