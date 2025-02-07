@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Empréstimos</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <a href="{{ route('emprestimos.create') }}" class="btn btn-primary mb-3">Registrar Novo Empréstimo</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Livro</th>
                <th>Data de Empréstimo</th>
                <th>Data de Devolução</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emprestimos as $emprestimo)
                <tr>
                    <td>{{ $emprestimo->id }}</td>
                    <td>{{ $emprestimo->usuario->nome }}</td>
                    <td>{{ $emprestimo->livro->titulo }}</td>
                    <td>{{ $emprestimo->data_emprestimo }}</td>
                    <td>
                        {{ $emprestimo->data_devolucao ? $emprestimo->data_devolucao : 'Em aberto' }}
                    </td>
                    <td>
                        @if(!$emprestimo->data_devolucao)
                            <a href="{{ route('emprestimos.devolver', $emprestimo->id) }}" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Confirmar a devolução deste livro?')">
                                Registrar Devolução
                            </a>
                        @else
                            <span class="text-muted">Devolvido</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
