@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Livro</h1>

    <!-- Formulário para atualização do livro -->
    <form action="{{ route('livros.update', $livro->id) }}" method="POST">
        @csrf
        @method('PUT')

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

        <div class="mb-3">
            <label for="categorias" class="form-label">Categorias</label>
            <select class="form-control" name="categorias[]" multiple>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, $livro->categorias->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Atualizar Livro</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <!-- Formulário para exclusão do livro -->
    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</button>
    </form>
</div>
@endsection
