<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Usuario;
use App\Models\Livro;
use Illuminate\Http\Request;
use Carbon\Carbon;


class EmprestimoController extends Controller
{

    public function index(Request $request)
    {
        $query = Emprestimo::with(['usuario', 'livro']); // Carregar relacionamento

        // Filtrando por usuário, se houver
        if ($request->filled('usuario')) {
            $query->where('usuario_id', $request->usuario);
        }

        // Obter os empréstimos com base na filtragem
        $emprestimos = $query->get();

        // Obter todos os usuários para o filtro
        $usuarios = Usuario::all(); // Certifique-se de que você tem o modelo Usuario

        return view('emprestimos.index', compact('emprestimos', 'usuarios'));
    }


    // Exibe o formulário de cadastro de empréstimo
    public function create()
    {
        // Busca todos os usuários e livros para preencher os selects
        $usuarios = Usuario::orderBy('nome', 'asc')->get();
        $livros = Livro::orderBy('titulo', 'asc')->get();
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

        Emprestimo::create($validated); // Criação de um empréstimo

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

    // Atualiza as informações do empréstimo
    public function update(Request $request, $id)
    {
        $emprestimo = Emprestimo::findOrFail($id);

        $validated = $request->validate([
            'usuario_id'       => 'required|exists:usuarios,id',
            'livro_id'         => 'required|exists:livros,id',
            'data_emprestimo'  => 'required|date',
            'data_devolucao'   => 'nullable|date', // data_devolucao é opcional
        ]);

        // Atualiza o empréstimo com os dados validados
        $emprestimo->update($validated);

        return redirect()->route('emprestimos.index')
            ->with('success', 'Empréstimo atualizado com sucesso!');
    }

    public function massDestroy(Request $request)
    {
        $ids = $request->input('emprestimos'); // IDs dos empréstimos selecionados

        if ($ids) {
            Emprestimo::destroy($ids); // Exclui os empréstimos selecionados
            return redirect()->route('emprestimos.index')->with('success', 'Empréstimos excluídos com sucesso!');
        }

        return redirect()->route('emprestimos.index')->with('warning', 'Nenhum empréstimo selecionado.');
    }

    public function destroy(Emprestimo $emprestimo)
    {
        // Exclui o empréstimo
        $emprestimo->delete();

        // Redireciona com uma mensagem de sucesso
        return redirect()->route('emprestimos.index')->with('success', 'Empréstimo excluído com sucesso!');
    }
    public function marcarComoDevolvido(Request $request, $id)
    {
        $emprestimo = Emprestimo::findOrFail($id);

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
