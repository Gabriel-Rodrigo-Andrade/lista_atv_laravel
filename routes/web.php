<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });
Route::get('/sobre', function () { return 'Sobre o projeto de alunos'; });
Route::get('/alunos', function () { return 'Lista de alunos'; });
Route::get('/contato', function () { return 'Contato da escola'; });
Route::get('/produto/{id}', function (string $id) { return 'Produto: '.$id; });
Route::get('/categoria/{id}', function (string $id) { return 'Categoria: '.$id; });
Route::get('/usuario/{id}', function (string $id) { return 'Usuario: '.$id; });
