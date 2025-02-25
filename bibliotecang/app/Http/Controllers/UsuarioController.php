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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:usuarios,email',
            'telefone' => ['nullable', 'string', function ($attribute, $value, $fail) {
                $numero = preg_replace('/\D/', '', $value); 
                if (strlen($numero) !== 11 && strlen($numero) > 0) {
                    $fail('O telefone deve ter 11 dígitos.');
                }
            }],
        ]);

        Usuario::create($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    // Exibe o formulário de edição do usuário
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
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:usuarios,email,' . $id, // Excluir a validação de unique para o próprio usuário
            'telefone' => ['nullable', 'string', function ($attribute, $value, $fail) {
                $numero = preg_replace('/\D/', '', $value); 
                if (strlen($numero) !== 11 && strlen($numero) > 0) {
                    $fail('O telefone deve ter 11 dígitos.');
                }
            }],
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
    

    // Exclui um usuário
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
