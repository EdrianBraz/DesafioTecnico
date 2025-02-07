@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Empréstimo</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('emprestimos.store') }}">
        @csrf
        <div class="mb-3">
            <label for="usuario_id" class="form-label">Usuário</label>
            <select class="form-control" id="usuario_id" name="usuario_id" required>
                <option value="">Selecione um usuário</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="livro_id" class="form-label">Livro</label>
            <select class="form-control" id="livro_id" name="livro_id" required>
                <option value="">Selecione um livro</option>
                @foreach($livros as $livro)
                    <option value="{{ $livro->id }}">{{ $livro->titulo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="data_emprestimo" class="form-label">Data de Empréstimo</label>
            <input type="date" class="form-control" id="data_emprestimo" name="data_emprestimo" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Empréstimo</button>
    </form>
</div>
@endsection
