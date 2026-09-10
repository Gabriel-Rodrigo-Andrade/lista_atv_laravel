@extends('layouts.app')
@section('title', 'Editar aluno')
@section('content')
<h1>Editar aluno</h1>
<form method="POST" action="{{ route('alunos.update', $aluno) }}">
    @method('PUT')
    @include('alunos._form')
</form>
@endsection
