@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuário: {{ $usuario->nome }}</h1>

    <!-- Formulário para edição de usuário -->
    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PATCH') <!-- Método PATCH para atualização -->

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" value="{{ old('nome', $usuario->nome) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" value="{{ old('telefone', $usuario->telefone) }}">
        </div>

        <button type="submit" class="btn btn-success mt-3">Salvar Alterações</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
