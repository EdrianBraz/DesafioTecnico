<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\HomeController;


// Rota para marcar o empréstimo como devolvido
Route::patch('/emprestimos/{id}/devolver', [EmprestimoController::class, 'devolver'])->name('emprestimos.devolver');

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// 📚 Rotas de Livros
Route::resource('livros', LivroController::class);
Route::get('/livros/importar-sinopse/{isbn}', [LivroController::class, 'importarSinopse'])->name('livros.importarSinopse');

// 👥 Rotas de Usuários
Route::resource('usuarios', UsuarioController::class);

// Excluir empréstimos em massa
Route::delete('/emprestimos', [EmprestimoController::class, 'massDestroy'])->name('emprestimos.massDestroy');


// 📊 Rotas de Relatórios
Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorio.index');
Route::get('/relatorio/pdf', [RelatorioController::class, 'gerarPdf'])->name('relatorio.pdf');

// 👥 Rotas de Empréstimo
Route::resource('emprestimos', EmprestimoController::class);
