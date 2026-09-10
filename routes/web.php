<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/sobre', function () { return 'Sobre o projeto de alunos'; });
Route::resource('alunos', \App\Http\Controllers\AlunoController::class)->middleware('auth');
Route::get('/contato', function () { return 'Contato da escola'; });
Route::get('/produto/{id}', function (string $id) { return 'Produto: '.$id; });
Route::get('/categoria/{id}', function (string $id) { return 'Categoria: '.$id; });
Route::get('/usuario/{id}', function (string $id) { return 'Usuario: '.$id; });

Route::resource('cursos', \App\Http\Controllers\CursoController::class)->only(['index', 'show'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::view('/admin', 'admin')->middleware(['auth', 'role:admin'])->name('admin');
Route::view('/professor', 'professor')->middleware(['auth', 'role:professor,admin'])->name('professor');
