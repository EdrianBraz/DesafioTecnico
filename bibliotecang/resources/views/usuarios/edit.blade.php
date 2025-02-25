<div class="container">
    <h1>Editar Usuário: {{ $usuario->nome }}</h1>
    <form id="UsuarioForm" method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
        @csrf
        @method('PATCH')  <!-- Aqui indicamos que o método HTTP será PATCH -->

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $usuario->nome) }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" value="{{ old('telefone', $usuario->telefone) }}" placeholder="(99) 99999-9999">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
