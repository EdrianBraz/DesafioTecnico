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

    <form method="GET" action="{{ route('emprestimos.index') }}">
        <div class="row">
            <div class="col mb3">
                <label for="usuario">Filtrar por Usuário</label>
                <select name="usuario" id="usuario" class="form-control" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ request('usuario') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col mb-3">
                <label for="status">Filtrar por Status</label>
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Em aberto</option>
                    <option value="devolvido" {{ request('status') == 'devolvido' ? 'selected' : '' }}>Devolvidos</option>
                </select>
            </div>
        </div>
    </form>
    <form id="emprestimos-form" method="POST" action="{{ route('emprestimos.massDestroy') }}">
        @csrf
        @method('DELETE')
        <div class="row">
            <div class="col d-flex justify-content-start">
                <a href="{{ route('emprestimos.create') }}" class="btn btn-primary mb-3">Registrar Novo Empréstimo</a>
            </div>
            <!-- Botões de Ação -->

            <div class="col d-flex justify-content-end">


                <button type="button" class="btn btn-secondary mb-3" id="selecionar-btn">Selecionar</button>

                <button type="submit" class="btn btn-danger d-none mb-3" id="excluir-btn" disabled>Excluir Selecionados</button>

            </div>
        </div>
        <table class="table table-bordered">
            <!-- Cabeçalho da tabela -->
            <thead>
                <tr>
                    <th class="checkbox-column d-none">
                        <input type="checkbox" id="select-all" />
                    </th>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Livro</th>
                    <th>Data de Empréstimo</th>
                    <th>Data de Devolução</th>
                </tr>
            </thead>
    </form>
    <tbody>
        @foreach($emprestimos as $emprestimo)
        <tr class="{{ $emprestimo->data_devolucao ? 'devolvido' : 'em-aberto' }}">
            <td class="checkbox-column d-none">
                <input type="checkbox" name="emprestimos[]" value="{{ $emprestimo->id }}" class="select-item" />
            </td>
            <td>{{ $emprestimo->id }}</td>
            <td>{{ $emprestimo->usuario->nome }}</td>
            <td>{{ $emprestimo->livro->titulo }}</td>
            <td>{{ $emprestimo->data_emprestimo }}</td>
            <td>
                {{ $emprestimo->data_devolucao ? $emprestimo->data_devolucao : 'Em aberto' }}
            </td>
            @if(!$emprestimo->data_devolucao) <!-- Adiciona botão "Marcar como Devolvido" somente para empréstimos não devolvidos -->
            <td>
                <form action="{{ route('emprestimos.devolver', $emprestimo->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning btn-sm">Devolver</button>
                </form>

            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
    </table>
    </form>

</div>

<script>
    // Função que filtra os empréstimos visíveis
    function filtrarStatus() {
        var status = "{{ request('status') }}"; // Pega o status selecionado
        var emprestimos = document.querySelectorAll('tr');

        // Oculta empréstimos devolvidos inicialmente
        emprestimos.forEach(function(row) {
            if (row.classList.contains('devolvido')) {
                row.style.display = 'none'; // Oculta empréstimos devolvidos
            }
        });

        // Mostra/oculta as linhas baseadas no status
        emprestimos.forEach(function(row) {
            if (status === 'aberto' && row.classList.contains('em-aberto')) {
                row.style.display = ''; // Exibe empréstimos em aberto
            } else if (status === 'devolvido' && row.classList.contains('devolvido')) {
                row.style.display = ''; // Exibe empréstimos devolvidos
            } else if (status === '') {
                row.style.display = ''; // Exibe todos
            } else {
                row.style.display = 'none'; // Oculta a linha que não corresponde ao filtro
            }
        });
    }

    // Chama a função ao carregar a página
    window.onload = filtrarStatus;
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let selecionarBtn = document.getElementById('selecionar-btn');
        let excluirBtn = document.getElementById('excluir-btn');
        let devolverBtn = document.getElementById('devolver-btn');
        let checkboxes = document.querySelectorAll('.select-item');
        let checkboxColumn = document.querySelectorAll('.checkbox-column');

        function atualizarBotoes() {
            let selecionados = document.querySelectorAll('.select-item:checked');
            let algumSelecionado = selecionados.length > 0;

            excluirBtn.disabled = !algumSelecionado;
            devolverBtn.disabled = !algumSelecionado;
        }

        selecionarBtn.addEventListener('click', function() {
            // Alterna a visibilidade das checkboxes
            checkboxColumn.forEach(col => col.classList.toggle('d-none'));

            // Exibe os botões de ação
            excluirBtn.classList.toggle('d-none');
            devolverBtn.classList.toggle('d-none');

            // Alterna o texto do botão entre "Selecionar" e "Cancelar"
            if (selecionarBtn.innerText === "Selecionar") {
                selecionarBtn.innerText = "Cancelar";
            } else {
                selecionarBtn.innerText = "Selecionar";

                // Desmarca todas as checkboxes e desativa os botões
                checkboxes.forEach(cb => cb.checked = false);
                atualizarBotoes();
            }
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', atualizarBotoes);
        });
    });
</script>

@endsection