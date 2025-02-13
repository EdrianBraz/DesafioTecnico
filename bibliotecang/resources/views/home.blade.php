@extends('layouts.app')
@section('content')

<head>


    <style>
        /* Cards informativos */
        .info-card {
            background: #ffffff;
            color: rgb(100, 100, 100);
            padding: 20px;
            border-radius: 0 0 2px 2px;
            text-align: center;
            box-shadow: 0px 0px 9px rgba(0, 6, 0, 0.1);
        }

        .info-card-register-users {
            background: rgb(0, 147, 245);
            color: white;
            padding: 5px;
            border-radius: 2px 2px 0 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .info-card-register-book {
            background: rgb(36, 154, 69);
            color: white;
            padding: 5px;
            border-radius: 2px 2px 0 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .info-unidades {
            font-size: 30px;
        }

        .info-acervo {
            background: rgb(91, 70, 152);
            font-size: 30px;
            text-align: center;
            color: rgb(255, 255, 255);
        }

        .info-card i {
            font-size: 25px;
            margin: 8px;
        }


        /* Definindo o comportamento para o carrossel */
        .carousel-inner {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            /* Impede o corte dos itens fora da área visível */
        }

        .carousel-item {
            display: flex;
            justify-content: center;
            transition: transform 1s ease-in-out;
            /* Transição suave */
            flex: 0 0 auto;
            /* Para os itens não ficarem com tamanho flexível */
            margin: 0 20px;
        }

        .carousel-item-book {
            transform: scale(0.8);
            /* Livros menores por padrão */
            transition: transform 0.3s ease-in-out;
            margin: 0 -5px;
            /* Ajustando espaçamento entre os livros */
            flex: 0 0 auto;
        }

        .carousel-item-book.active {
            transform: scale(1);
            /* Livro central maior */
        }

        /* Ajuste de espaçamento entre os itens */
        .carousel-control-prev,
        .carousel-control-next {
            z-index: 1;
            /* Garantir que os controles fiquem acima do carrossel */
        }

        /* Ajuste da altura do carrossel */
        #livrosCarrossel {
            max-height: 350px;
            /* Ajuste conforme necessário */
        }

        a.text-decoration-none {
            color: inherit;
        }
    </style>
</head>

<body>

    <div class="row mb-4">

        <div class="col-md-6">
            <div class="info-card-register-users">
                <!-- Ícone agora é um link para a página de cadastro -->
                <p></p>
                <h5>Usuários Cadastrados</h5>
                <a href="{{ route('usuarios.index') }}" class="text-decoration-none">
                    <p class="info-unidades"> <i class="fas fa-user-plus"></i> {{ $totalUsuarios }} </p>
            </div>
            </a>
            <div class="info-card">
                <!-- Menu Rápido -->
                <div class="dropdown">
                    <h5>Cadastrar Usuario</h5>
                    <form method="POST" action="{{ route('usuarios.store') }}">
                        @csrf
                        <div class="mb-1">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-1">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-1">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Cadastrar</button>
                    </form>
                </div>

                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card-register-book">
                <p></p>
                <h5>Empréstimos Ativos</h5>
                <a href="{{ route('emprestimos.index') }}" class="text-decoration-none">
                    <p class="info-unidades">
                        <i class="fas fa-handshake"></i> {{ $totalEmprestimos }}
                    </p>
                </a>

            </div>
            <!-- Formulário de Empréstimo -->
            <div class="info-card">
                <h5>Realizar Empréstimo</h5>
                <form method="POST" action="{{ route('emprestimos.store') }}">
                    @csrf
                    <div class="mb-1">
                        <label for="usuario_id" class="form-label">Usuário</label>
                        <select class="form-control" id="usuario_id" name="usuario_id" required>
                            <option value="">Selecione um usuário</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
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
                    <div class="mb-1">
                        <label for="data_emprestimo" class="form-label">Data de Empréstimo</label>
                        <input type="date" class="form-control" id="data_emprestimo" name="data_emprestimo" value="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Realizar Empréstimo</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <p class="info-acervo"> Livros no Acervo <i class="fas fa-book"></i> {{ $totalLivros }}</p>
        <div class="owl-carousel owl-theme">
            @foreach($livrosMaisEmprestados as $livro)
            <div class="item">
                <a href="{{ route('livros.show', $livro->id) }}">
                    <img src="{{ $livro->capa }}" class="d-block mx-auto rounded shadow-lg" alt="{{ $livro->titulo }}"
                        style="width: 200px; height: 300px; object-fit: cover;">
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        });
    </script>

</body>
@endsection