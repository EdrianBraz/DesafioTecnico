@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Usuários</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#UsuariosModal">
        Cadastrar Novo Usuário
    </button>

    <!-- Modal para Criar/Editar Usuário -->
    <div class="modal fade" id="UsuariosModal" tabindex="-1" aria-labelledby="UsuariosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="UsuariosModalLabel">Cadastrar ou Editar Usuário</h5>
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

    <!-- Tabela de Usuários -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->nome }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->telefone }}</td>
                    <td>
                        <!-- Botão Editar -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#UsuariosModal"
                            data-id="{{ $usuario->id }}"
                            data-nome="{{ $usuario->nome }}"
                            data-email="{{ $usuario->email }}"
                            data-telefone="{{ $usuario->telefone }}">
                            Editar
                        </button>

                        <!-- Formulário para Excluir -->
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById('UsuariosModal');

    modal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // O botão que abriu o modal
        var userId = button.getAttribute('data-id'); // ID do usuário (caso de edição)
        var userNome = button.getAttribute('data-nome');
        var userEmail = button.getAttribute('data-email');
        var userTelefone = button.getAttribute('data-telefone');
        
        // Determina a URL do formulário dependendo da ação (criar ou editar)
        fetch(userId ? "{{ route('usuarios.edit', ':id') }}".replace(':id', userId) : "{{ route('usuarios.create') }}")
            .then(response => {
                if (!response.ok) throw new Error("Erro ao carregar o formulário");
                return response.text();
            })
            .then(html => {
                document.getElementById('form-container').innerHTML = html;

                const telefoneInput = document.querySelector('input[name="telefone"]');
                if (telefoneInput) {
                    telefoneInput.addEventListener('input', function() {
                        let numero = telefoneInput.value.replace(/\D/g, "");

                        if (numero.length > 11) numero = numero.slice(0, 11);

                        if (numero.length === 11) {
                            telefoneInput.value = numero.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                        } else if (numero.length === 10) {
                            telefoneInput.value = numero.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
                        }
                    });

                    // Preenche os dados do usuário caso seja edição
                    if (userId) {
                        document.querySelector('input[name="nome"]').value = userNome;
                        document.querySelector('input[name="email"]').value = userEmail;
                        document.querySelector('input[name="telefone"]').value = userTelefone;
                    }
                }

                // Validação do telefone no envio do formulário
                const form = document.getElementById('UsuarioForm');
                if (form) {
                    form.addEventListener('submit', function(event) {
                        let telefone = document.getElementById('telefone').value(/\D/g, "");
                        if (telefone.length !== 11 && telefone.length > 0) {
                            alert("O telefone deve ter 11 dígitos. Ex: (99) 99999-9999");
                            event.preventDefault();
                            return;
                        }

                        document.getElementById('telefone').value = telefone; // Atualiza o valor do campo
                    });
                }
            })
            .catch(error => console.error('Erro:', error));
    });
});
</script>
