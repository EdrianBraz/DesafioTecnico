@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cadastrar Livro</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('livros.store') }}">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" class="form-control" id="autor" name="autor" required>
        </div>
        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" required>
        </div>
        <div class="mb-3">
            <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
            <input type="number" class="form-control" id="ano_publicacao" name="ano_publicacao" required>
        </div>
        <div class="mb-3">
    <label class="form-label">Categorias</label>
    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        @foreach ($categorias as $categoria)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="categorias_existentes[]" value="{{ $categoria->id }}" id="categoria_{{ $categoria->id }}">
                <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                    {{ $categoria->nome }}
                </label>
            </div>
        @endforeach
    </div>
</div>
<div class="mb-3">
            <label for="nova_categoria" class="form-label">Nova Categoria (Opcional)</label>
            <input type="text" class="form-control" id="nova_categoria" name="nova_categoria" placeholder="Digite uma nova categoria">
        </div>
        <div class="mb-3">
            <label for="quantidade_estoque" class="form-label">Quantidade em Estoque</label>
            <input type="number" class="form-control" id="quantidade_estoque" name="quantidade_estoque" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>
@endsection
