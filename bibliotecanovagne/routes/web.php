<?php
use App\Http\Controllers\LivroController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmprestimoController;

Route::get('/', function () {
    return view('home');
});

Route::resource('livros', LivroController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('emprestimos', EmprestimoController::class);
Route::get('emprestimos/{id}/devolver', [EmprestimoController::class, 'devolver'])
     ->name('emprestimos.devolver');


     