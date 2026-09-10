<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });
Route::get('/sobre', function () { return 'Sobre o projeto de alunos'; });
Route::get('/alunos', function () { return 'Lista de alunos'; });
Route::get('/contato', function () { return 'Contato da escola'; });
