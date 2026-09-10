@extends('layouts.app')
@section('title', 'Cursos')
@section('content')
<h1>Cursos</h1>
@foreach ($cursos as $curso)
    <p><a href="{{ route('cursos.show', $curso) }}">{{ $curso->nome }}</a> — {{ $curso->alunos_count }} alunos</p>
@endforeach
@endsection
