<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
        // Lista todos os usuários
    public function index()
    {
        $usuarios = Usuario::orderBy('nome', 'asc')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    // Exibe o formulário de cadastro de usuário
    public function create()
    {
        return view('usuarios.create');
    }

    // Processa o formulário e armazena o usuário no banco de dados
    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:usuarios,email',
            'telefone'=> 'nullable|string|max:20',
        ]);

        Usuario::create($validated);
        
        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }
        // Método para exibir o formulário de edição do usuário
        public function edit($id)
        {
            // Encontra o usuário pelo ID
            $usuario = Usuario::findOrFail($id);
    
            // Retorna a view com o usuário para edição
            return view('usuarios.edit', compact('usuario'));
        }

        public function update(Request $request, $id)
{
    // Validação dos campos
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:usuarios,email,' . $id,
        'telefone' => 'nullable|string|max:20',
    ]);

    // Encontrar o usuário pelo ID
    $usuario = Usuario::findOrFail($id);

    // Atualizar os dados do usuário
    $usuario->update([
        'nome' => $request->input('nome'),
        'email' => $request->input('email'),
        'telefone' => $request->input('telefone'),
    ]);

    // Redirecionar de volta com mensagem de sucesso
    return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
}
public function destroy($id)
{
    // Encontrar o usuário pelo ID
    $usuario = Usuario::findOrFail($id);

    // Excluir o usuário
    $usuario->delete();

    // Redirecionar com uma mensagem de sucesso
    return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
}


}