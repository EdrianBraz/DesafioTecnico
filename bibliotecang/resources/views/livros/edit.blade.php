@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Livro: {{ $livro->titulo }}</h1>

    <!-- Formulário para edição de livro -->
    <form action="{{ route('livros.update', $livro->id) }}" method="POST">
        @csrf
        @method('PATCH') <!-- Método PATCH para atualização -->

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" name="titulo" value="{{ old('titulo', $livro->titulo) }}" required>
        </div>

        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" class="form-control" name="autor" value="{{ old('autor', $livro->autor) }}" required>
        </div>

        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" name="isbn" value="{{ old('isbn', $livro->isbn) }}">
        </div>

        <div class="mb-3">
            <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
            <input type="number" class="form-control" name="ano_publicacao" value="{{ old('ano_publicacao', $livro->ano_publicacao) }}" required>
        </div>

        <div class="mb-3">
            <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
            <input type="number" class="form-control" name="quantidade_estoque" value="{{ old('quantidade_estoque', $livro->quantidade_estoque) }}" required>
        </div>

        <label class="form-label">Categorias</label>
        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
            @foreach ($categorias as $categoria)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="categorias_existentes[]" value="{{ $categoria->id }}" id="categoria_{{ $categoria->id }}"
                        {{ in_array($categoria->id, old('categorias_existentes', $livro->categorias->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                        {{ $categoria->nome }}
                    </label>
                </div>
            @endforeach
        </div>
<div>
        <button type="submit" class="btn btn-success mt-3">Salvar Alterações</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

    <!-- Formulário para deletar o livro -->
    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3 " onclick="return confirm('Tem certeza que deseja excluir este livro?')">Deletar Livro</button>
    </form>
    <div>
</div>
@endsection
