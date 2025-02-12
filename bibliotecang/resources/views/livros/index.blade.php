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

    <!-- Filtro de livros -->
    <form method="GET" action="{{ route('livros.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="titulo" class="form-control" placeholder="Filtrar por Título" value="{{ request('titulo') }}">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="autor" class="form-control" placeholder="Filtrar por Autor" value="{{ request('autor') }}">
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                <a href="{{ route('livros.index') }}" class="btn btn-secondary btn-block">Limpar</a>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <label for="categorias">Categorias</label>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Selecionar Categorias
                </button>
                <ul class="dropdown-menu w-100">
                    @foreach($categorias as $categoria)
                    <li>
                        <label class="dropdown-item">
                            <button type="submit" name="categorias[]" value="{{ $categoria->id }}" class="dropdown-item">{{ $categoria->nome }}</button>
                        </label>
                    </li>
                    @endforeach
                </ul>
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
                <th>Capa</th>
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
                        'ordenar_por' => 'quantidade_estoque',
                        'ordem' => (request('ordenar_por') == 'quantidade_estoque' && request('ordem') == 'asc') ? 'desc' : 'asc'
                    ])) }}">
                        Quantidade em Estoque
                    </a>
                </th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livros as $livro)
            <tr>
                <td>{{ $livro->id }}</td>
                <td>
                    @if($livro->capa)
                    <!-- Capa clicável que abre o modal -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $livro->id }}">
                        <img src="{{ $livro->capa }}" onerror="this.onerror=null;this.src='{{ asset('images/default.jpg') }}';" alt="Capa do Livro" width="50">
                    </a>
                    @else
                    <img src="{{ asset('images/default.jpg') }}" alt="Capa do Livro" width="50">
                    @endif
                </td>
                <td>{{ $livro->titulo }}</td>
                <td>{{ $livro->autor }}</td>
                <td>{{ $livro->isbn }}</td>
                <td>{{ $livro->ano_publicacao }}</td>
                <td>
                    @foreach ($livro->categorias as $categoria)
                    {{ $categoria->nome }}@if (!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $livro->quantidade_estoque }}</td>
                <td>
                    <a href="{{ route('livros.edit', $livro->id) }}" class="btn btn-light btn-sm">Editar</a>
                </td>
            </tr>

            <!-- Modal de Exibição da Capa -->
            <div class="modal fade" id="imageModal-{{ $livro->id }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $livro->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel-{{ $livro->id }}">Capa do Livro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ $livro->capa }}" onerror="this.onerror=null;this.src='{{ asset('images/default.jpg') }}';"  alt="Capa do Livro" style="max-width: 100%;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<!-- Adicionar o JS do Bootstrap para que o modal funcione -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection