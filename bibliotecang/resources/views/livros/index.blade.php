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


        </div>
    </form>
    <div class="row mb-3">
        <div class="col-md-3">

            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#livroModal">
                Cadastrar Novo Livro
            </button>

            <!-- Modal -->

                <div class="modal fade" id="livroModal" tabindex="-1" aria-labelledby="livroModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="livroModalLabel">Cadastrar Novo Livro</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <div id="form-container">
                                    <p>Carregando...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            <div class="col-md-3">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Selecionar Categorias
                    </button>
                    <ul class="dropdown-menu w-200">
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
        </div>


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
                            Unidades / Disponiveis
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
                            <img src="{{ $livro->capa }}" onerror="this.onerror=null;this.src=this.getAttribute('data-default-image');" data-default-image="{{ asset('images/default.jpg') }}" alt="Capa do Livro" width="50">
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
                    <td>{{ $livro->quantidade_estoque }} / {{ $livro->quantidadeDisponivel() }}</td>
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
                                <div class="row">
                                    <!-- Coluna da imagem -->
                                    <div class="col-md-4">
                                        <img src="{{ $livro->capa }}" onerror="this.onerror=null;this.src=this.getAttribute('data-default-image');" data-default-image="{{ asset('images/default.jpg') }}" alt="Capa do Livro" style="max-width: 100%;">
                                    </div>

                                    <!-- Coluna das informações do livro -->
                                    <div class="col-md-8">
                                        <h2>{{ $livro->titulo }}</h2>
                                        <p><strong>Autor:</strong> {{ $livro->autor }}</p>
                                        <p><strong>ISBN:</strong> {{ $livro->isbn }}</p>
                                        <p><strong>Ano de Publicação:</strong> {{ $livro->ano_publicacao }}</p>
                                        <p><strong>Categorias:</strong>
                                            @foreach($livro->categorias as $categoria)
                                            <span class="badge bg-secondary">{{ $categoria->nome }}</span>
                                            @endforeach
                                        </p>

                                        <!-- Exibindo a sinopse do livro -->
                                        <p><strong>Sinopse:</strong></p>
                                        <p>{{ $livro->sinopse ? $livro->sinopse : 'Sinopse não disponível.' }}</p>

                                    </div>
                                </div>
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var livroModal = document.getElementById('livroModal');

            livroModal.addEventListener('show.bs.modal', function() {
                fetch("{{ route('livros.create') }}")
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('form-container').innerHTML = html;
                    })
                    .catch(error => console.log('Erro ao carregar o formulário:', error));
            });
        });
    </script>


    <script>
        document.addEventListener("submit", function(event) {
            if (event.target.id === "livroForm") {
                event.preventDefault();

                let form = event.target;
                let formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert("Livro cadastrado com sucesso!");
                        document.getElementById('livroModal').querySelector('.btn-close').click();
                        // Aqui podes atualizar a tabela/lista de livros sem recarregar a página
                    })
                    .catch(error => console.log("Erro ao salvar:", error));
            }
        });
    </script>


    @endsection