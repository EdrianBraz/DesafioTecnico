@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Livros</h1>

    <!-- Mensagem de sucesso -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="GET" action="{{ route('livros.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="titulo" class="form-control" placeholder="Filtrar por Título" value="{{ request('titulo') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="autor" class="form-control" placeholder="Filtrar por Autor" value="{{ request('autor') }}">
            </div>
            <div class="col-md-3 mb-2">
                <select name="categoria" class="form-control">
                    <option value="">Selecione a Categoria</option>
                    @foreach($categorias as $item)
                        @if(is_object($item))
                            <option value="{{ $item->categoria }}" @if(request('categoria') == $item->categoria) selected @endif>
                                {{ $item->categoria }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                <a href="{{ route('livros.index') }}" class="btn btn-secondary btn-block">Limpar</a>
            </div>
        </div>
    </form>

    <!-- Link para cadastrar um novo livro -->
    <a href="{{ route('livros.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Livro</a>

    <!-- Tabela de livros -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>
                    <a href="{{ route('livros.index', array_merge(request()->all(), [
                        'ordenar_por' => 'titulo',
                        'ordem' => (request('ordenar_por') == 'titulo' && request('ordem') == 'asc') ? 'desc' : 'asc'
                    ])) }}">
                        Título
                    </a>
                </th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Ano de Publicação</th>
                <th>Categoria</th>
                <th>
                    <a href="{{ route('livros.index', array_merge(request()->all(), [
                        'ordenar_por' => 'estoque',
                        'ordem' => (request('ordenar_por') == 'estoque' && request('ordem') == 'asc') ? 'desc' : 'asc'
                    ])) }}">
                        Quantidade em Estoque
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($livros as $livro)
            <tr>
                <td>{{ $livro->id }}</td>
                <td>{{ $livro->titulo }}</td>
                <td>{{ $livro->autor }}</td>
                <td>{{ $livro->isbn }}</td>
                <td>{{ $livro->ano_publicacao }}</td>
                <td>{{ $livro->categoria }}</td>
                <td>{{ $livro->quantidade_estoque }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
