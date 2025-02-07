<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Usuario;
use App\Models\Livro;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmprestimoController extends Controller
{
    // Lista todos os empréstimos
    public function index()
    {
        $emprestimos = Emprestimo::with(['usuario', 'livro'])->get();
        return view('emprestimos.index', compact('emprestimos'));
    }

    // Exibe o formulário de cadastro de empréstimo
    public function create()
    {
        // Busca todos os usuários e livros para preencher os selects
        $usuarios = Usuario::all();
        $livros = Livro::all();
        return view('emprestimos.create', compact('usuarios', 'livros'));
    }

    // Processa o formulário e registra o empréstimo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id'       => 'required|exists:usuarios,id',
            'livro_id'         => 'required|exists:livros,id',
            'data_emprestimo'  => 'required|date',
        ]);

        Emprestimo::create($validated);

        return redirect()->route('emprestimos.index')
                         ->with('success', 'Empréstimo registrado com sucesso!');
    }

    // Registra a devolução do livro
    public function devolver($id)
    {
        $emprestimo = Emprestimo::findOrFail($id);

        // Se o livro já foi devolvido, podemos impedir a ação
        if ($emprestimo->data_devolucao) {
            return redirect()->route('emprestimos.index')
                             ->with('warning', 'Este empréstimo já foi devolvido.');
        }

        $emprestimo->update([
            'data_devolucao' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('emprestimos.index')
                         ->with('success', 'Livro devolvido com sucesso!');
    }
}
