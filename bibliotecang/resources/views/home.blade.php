@extends('layouts.app')
@section('content')

<head>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fc;
            color: #333;
        }

        /* Variáveis de cores */
        :root {
            --primary-color: #3498db;
            --secondary-color: #8e44ad;
            --accent-color: #f1c40f;
            --card-bg: #ffffff;
            --text-color: #333;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
        }

        /* Cards informativos */
        .info-card {
            background: var(--card-bg);
            color: #666;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px var(--shadow-light);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 20px var(--shadow-medium);
        }

        .info-card-register-users,
        .info-card-register-book {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            padding: 10px;
            border-radius: 12px 12px 0 0;
            text-align: center;
            box-shadow: 0px 4px 10px var(--shadow-light);
        }

        .info-card-register-users i,
        .info-card-register-book i {
            font-size: 30px;
            margin-bottom: 15px;
        }

        .info-card h5 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .info-unidades,
        .info-acervo {
            font-size: 2rem;
            font-weight: 700;
        }

        .info-acervo {
            background: linear-gradient(135deg, rgb(3, 39, 135), #29448E);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        /* Formulário */
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        /* Carrossel de livros */
        .owl-carousel .item img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .owl-carousel .item:hover img {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        /* Ajustes gerais */
        .row {
            margin-bottom: 30px;
        }

        .col-md-6,
        .col-md-12 {
            padding: 20px;
        }

        .owl-carousel {
            margin-top: 30px;
        }

        /* Layout de Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: #fff;
        }

        /* Ícones */
        .info-card i {
            font-size: 35px;
            color: #fff;
            transition: color 0.3s ease;
        }

        .info-card:hover i {
            color: var(--accent-color);
        }

        /* Pequenos ajustes */
        .text-decoration-none {
            color: inherit;
            transition: color 0.3s ease;
        }

        .text-decoration-none:hover {
            color: var(--primary-color);
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
                    <p class="info-unidades"><i class="fas fa-user-plus"></i> {{ $totalUsuarios }}</p>
                </a>
            </div>
            <div class="info-card">
                <!-- Menu Rápido -->
                <div id="form-container" class="dropdown">
                    <h5>Cadastrar Usuário</h5>
                    <form id="UsuarioForm" method="POST" action="{{ route('usuarios.store') }}">
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
                            <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15" placeholder="(99) 99999-9999">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Cadastrar</button>
                    </form>
                </div>
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
        <p class="info-acervo">Livros no Acervo <i class="fas fa-book"></i> {{ $totalLivros }}</p>
        <div class="owl-carousel owl-theme">
            @foreach($livrosMaisEmprestados as $livro)
            <div class="item">
                <a href="{{ route('livros.show', $livro->id) }}">
                    <img src="{{ $livro->capa }}" class="d-block mx-auto rounded shadow-lg" alt="{{ $livro->titulo }}" style="width: 200px; height: 300px; object-fit: cover;">
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

        document.addEventListener("DOMContentLoaded", function() {
            const telefoneInput = document.querySelector('input[name="telefone"]');
            const form = document.getElementById('UsuarioForm');

            if (telefoneInput) {
                // Máscara de telefone
                telefoneInput.addEventListener('input', function() {
                    let numero = telefoneInput.value.replace(/\D/g, "");

                    if (numero.length > 11) numero = numero.slice(0, 11);

                    if (numero.length === 11) {
                        telefoneInput.value = numero.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
                    } else if (numero.length === 10) {
                        telefoneInput.value = numero.replace(/^(\d{2})(\d{4})(\d{4})$/, "($1) $2-$3");
                    }
                });
            }

            // Validação do telefone no envio do formulário
            if (form) {
                form.addEventListener('submit', function(event) {
                    let telefone = document.getElementById('telefone').value.replace(/\D/g, "");
                    if (telefone.length !== 11 && telefone.length > 0) {
                        alert("O telefone deve ter 11 dígitos. Ex: (99) 99999-9999");
                        event.preventDefault();
                        return;
                    }

                    document.getElementById('telefone').value = telefone; // Atualiza o valor do campo
                });
            }
        });
    </script>
    @endsection