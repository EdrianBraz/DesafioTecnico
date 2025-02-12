@extends('layouts.app')

@section('content')
    <div class="text-center mt-5">
        <h1 class="display-4 font-weight-bold text-primary">Bem-vindo ao Sistema de Gerenciamento da Biblioteca</h1>
        <p class="lead text-muted">Encontre e gerencie seus livros com facilidade.</p>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4 text-center text-secondary">Livros Mais Emprestados</h2>
        
        <div id="livrosCarrossel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                @foreach($livrosMaisEmprestados as $index => $livro)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <a href="{{ route('livros.show', $livro->id) }}">
                            <img src="{{ $livro->capa }}" class="d-block mx-auto rounded shadow-lg" alt="{{ $livro->titulo }}" style="width: 200px; height: 300px; object-fit: cover;">
                        </a>
                    </div>
                @endforeach
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#livrosCarrossel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#livrosCarrossel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .carousel-inner {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .carousel-item img {
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item img:hover {
            transform: scale(1.1);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #007bff;
        }

        .carousel-control-prev,
        .carousel-control-next {
            z-index: 10;
        }

        h2 {
            font-size: 2.5rem;
            color: #333;
        }

        .container {
            max-width: 1200px;
        }

        .display-4 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .lead {
            font-size: 1.25rem;
        }

        .shadow-lg {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .rounded {
            border-radius: 10px;
        }
    </style>
@endsection
