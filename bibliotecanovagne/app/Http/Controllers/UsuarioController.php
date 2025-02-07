<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // Lista todos os usuários
    public function index()
    {
        $usuarios = Usuario::all();
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
}
