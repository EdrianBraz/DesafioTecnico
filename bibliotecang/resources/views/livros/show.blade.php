@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <img src="{{ $livro->capa }}" class="img-fluid rounded shadow" alt="{{ $livro->titulo }}">
        </div>
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
@endsection
