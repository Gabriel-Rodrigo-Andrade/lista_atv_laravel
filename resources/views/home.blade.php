@extends('layouts.app')
@section('title', 'Bem-vindo à escola')
@section('content')
<h1>Bem-vindo à escola</h1>
<p>Consulte cursos e alunos, cadastre novos alunos e acompanhe os professores responsáveis.</p>
<p><a href="{{ route('alunos.index') }}">Acessar alunos</a></p>
@endsection
