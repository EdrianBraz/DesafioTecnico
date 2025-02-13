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
            <option value="{{ $livro->id }}" data-quantidade="{{ $livro->quantidadeDisponivel() }}">
                {{ $livro->titulo }} (Disponíveis: {{ $livro->quantidadeDisponivel() }})
            </option>
        @endforeach
    </select>
</div>

        <div class="mb-3">
            <label for="data_emprestimo" class="form-label">Data de Empréstimo</label>
            <input type="date" class="form-control" id="data_emprestimo" name="data_emprestimo" value="{{ \Carbon\Carbon::today()->toDateString() }}" required>
        </div>
        <form method="POST" action="{{ route('emprestimos.store') }}">
    @csrf
    <button type="submit" class="btn btn-primary">Registrar Empréstimo</button>
    <a href="{{ route('emprestimos.index') }}" class="btn btn-secondary">Cancelar</a>
</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const livroBusca = document.getElementById('livro_busca');
    const livroSelect = document.getElementById('livro_id');
    
    // Função para filtrar os livros com base na pesquisa
    livroBusca.addEventListener('input', function() {
        const termoBusca = livroBusca.value.toLowerCase(); // Obtém o termo digitado
        
        // Itera sobre as opções do select
        Array.from(livroSelect.options).forEach(function(option) {
            const titulo = option.textContent.toLowerCase(); // Título do livro
            // Mostra ou esconde a opção dependendo se o título contém o termo de busca
            option.style.display = titulo.includes(termoBusca) ? 'block' : 'none';
        });
    });
});
</script>
<script>

    document.addEventListener("DOMContentLoaded", function() {
    const livroSelect = document.getElementById("livro_id");
    const quantidadeInput = document.getElementById("quantidade");
    const quantidadeInfo = document.getElementById("quantidade-info");

    // Atualizar quantidade máxima conforme o livro selecionado
    livroSelect.addEventListener("change", function() {
        const selectedOption = livroSelect.options[livroSelect.selectedIndex];
        const quantidadeDisponivel = selectedOption.getAttribute("data-quantidade");

        if (quantidadeDisponivel) {
            quantidadeInfo.textContent = `Disponíveis: ${quantidadeDisponivel}`;
            quantidadeInput.max = quantidadeDisponivel;
            quantidadeInput.value = 1; // Reseta para o mínimo
        }
    });
});

</script>
@endsection
