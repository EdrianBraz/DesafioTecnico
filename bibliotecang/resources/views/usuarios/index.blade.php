@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Usuários</h1>

    <!-- Mensagem de sucesso -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Link para cadastrar um novo usuário -->
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Usuário</a>

    <!-- Tabela de usuários -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nome }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->telefone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection