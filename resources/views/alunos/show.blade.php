@extends('layouts.app')
@section('title', 'Detalhes do aluno')
@section('content')
<h1>{{ $aluno->nome }}</h1>
<p>E-mail: {{ $aluno->email }}</p>
<p>Curso: {{ $aluno->curso->nome }}</p>
@endsection
