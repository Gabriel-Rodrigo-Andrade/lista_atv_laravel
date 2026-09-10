<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/sobre', function () {
    return 'Sobre o projeto de alunos';
});
Route::resource('alunos', AlunoController::class)->middleware('auth');
Route::get('/contato', function () {
    return 'Contato da escola';
});
Route::get('/produto/{id}', function (string $id) {
    return response('Produto: '.$id, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
});
Route::get('/categoria/{id}', function (string $id) {
    return response('Categoria: '.$id, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
});
Route::get('/usuario/{id}', function (string $id) {
    return response('Usuario: '.$id, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
});

Route::resource('cursos', CursoController::class)->only(['index', 'show'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::view('/admin', 'admin')->middleware(['auth', 'role:admin'])->name('admin');
Route::view('/professor', 'professor')->middleware(['auth', 'role:professor,admin'])->name('professor');
