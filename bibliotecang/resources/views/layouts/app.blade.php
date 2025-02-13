<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento da Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        /*fonte global para todo o site */
        body {
            font-family: "Roboto", serif;
            font-optical-sizing: auto;
        }

        /* Estilizando a sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: rgb(255, 255, 255);
            color: white;
            padding-top: 20px;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        /* Ajustando os links da sidebar */
        .sidebar a {
            color: rgb(103, 103, 103);
            text-decoration: none;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }

        .table>:not(caption)>*>* {
            color: rgb(85, 85, 85);
        }

        .sidebar a:hover {
            background: linear-gradient(135deg, rgb(111, 69, 214), rgb(77, 79, 198));
            color: white;
        }

        /* Estilizando o cabeçalho fixo */
        .header {
            position: fixed;
            /* Fixa o header no topo */
            top: 0;
            left: 0;
            width: 100%;
            /* Faz o header ocupar toda a largura */
            background: linear-gradient(135deg, rgb(90, 88, 94), rgb(51, 51, 51));
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 20px;
            font-weight: bold;
            z-index: 1000;
            /* Garante que o header fique acima de outros elementos */
        }

        /* Ajustando o conteúdo principal */
        .content {
            margin-top: 34px;
            margin-left: 240px;
            padding: 0px;
            background: rgb(249, 252, 255);
        }

        /* Responsividade */
        @media (max-width: 767px) {
            /* Sidebar fica no topo em telas menores */
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                top: 0;
                left: 0;
            }
            .content {
                margin-left: 0;
            }
            .sidebar a {
                font-size: 14px;
            }
        }

        /* Ajuste do menu dropdown para telas pequenas */
        @media (max-width: 767px) {
            .sidebar {
                display: none;
            }
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column flex-md-row">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="text-center mb-4">BIBLIOTECÁRIO</h4>
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('livros.index') }}"><i class="fas fa-book"></i> Livros</a>
            <a href="{{ route('usuarios.index') }}"><i class="fas fa-users"></i> Usuários</a>
            <a href="{{ route('emprestimos.index') }}"><i class="fas fa-handshake"></i> Empréstimos</a>
            <a href="{{ route('relatorio.index') }}"><i class="fas fa-chart-bar"></i> Relatórios</a>
        </div>

        <!-- Conteúdo Principal -->
        <div class="content w-100">
            <div class="header">Sistema Bibliotecário</div>
            <div class="container mt-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Botão de Toggle da Sidebar para dispositivos móveis -->
    <button class="btn btn-primary d-md-none sidebar-toggle" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Importando o JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (necessário para Owl Carousel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Scripts extras de cada página -->
    @yield('scripts')

</body>

</html>
