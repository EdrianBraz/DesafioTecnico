@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Novo Livro</h1>

    <!-- Formulário para criação de livro -->
    <form action="{{ route('livros.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" name="titulo" value="{{ old('titulo') }}" required>
        </div>

        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" class="form-control" name="autor" value="{{ old('autor') }}" required>
        </div>

        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" name="isbn" value="{{ old('isbn') }}">
        </div>

        <div class="mb-3">
            <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
            <input type="number" class="form-control" name="ano_publicacao" value="{{ old('ano_publicacao') }}" required>
        </div>

        <div class="mb-3">
            <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
            <input type="number" class="form-control" name="quantidade_estoque" value="{{ old('quantidade_estoque') }}" required>
        </div>

        <div class="mb-3">
            <label for="categorias" class="form-label">Categorias</label>
            <select class="form-control" name="categorias[]" multiple>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, old('categorias', [])) ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Criar Livro</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
