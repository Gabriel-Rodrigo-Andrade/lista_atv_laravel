@extends('layouts.app')
@section('title', 'Alunos')
@section('content')
<h1>Alunos</h1>
@if (count($alunos) === 0)
    <p>Nenhum aluno cadastrado.</p>
@else
    <ul>
        @foreach ($alunos as $aluno)
            <li>{{ $aluno['nome'] }}</li>
        @endforeach
    </ul>
@endif
@endsection
