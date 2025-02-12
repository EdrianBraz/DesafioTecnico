<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('home');
})->name('home');

// 📚 Rotas de Livros
Route::resource('livros', LivroController::class);
Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
Route::get('/livros/{id}', [LivroController::class, 'show'])->name('livros.show');

// 👥 Rotas de Usuários
Route::resource('usuarios', UsuarioController::class);
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');

// 📖 Rotas de Empréstimos
Route::resource('emprestimos', EmprestimoController::class);
Route::get('/emprestimos', [EmprestimoController::class, 'index'])->name('emprestimos.index');

// Rota para marcar o empréstimo como devolvido
Route::patch('/emprestimos/devolver/{id}', [EmprestimoController::class, 'marcarComoDevolvido'])
    ->name('emprestimos.marcarDevolvido');

// Excluir empréstimos em massa
Route::delete('/emprestimos', [EmprestimoController::class, 'massDestroy'])->name('emprestimos.massDestroy');

// 📊 Rotas de Relatórios
Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorio.index');
Route::get('/relatorio/pdf', [RelatorioController::class, 'gerarPdf'])->name('relatorio.pdf');
