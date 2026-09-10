@extends('layouts.app')
@section('title', $curso->nome)
@section('content')
<h1>{{ $curso->nome }}</h1>
@if ($curso->alunos->isEmpty())
    <p>Nenhum aluno neste curso.</p>
@endif
@foreach ($curso->alunos as $aluno)
    <p><a href="{{ route('alunos.show', $aluno) }}">{{ $aluno->nome }}</a> — {{ $aluno->email }}</p>
@endforeach
@endsection
