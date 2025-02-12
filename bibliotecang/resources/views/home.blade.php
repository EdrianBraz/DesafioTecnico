<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento da Biblioteca</title>
    <!-- Importando o Bootstrap via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Importando Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Importando o CSS customizado -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        /* Estilizando a sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #2c3e50;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }
        .sidebar a:hover {
            background: #1a252f;
        }
        /* Ajustando o conteúdo principal */
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        /* Estilizando o cabeçalho */
        .header {
            background: linear-gradient(135deg, #2980b9, #3498db);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        /* Cards informativos */
        .info-card {
            background: #2980b9;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .info-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
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
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-user-plus"></i>
                            <h5>Usuários Cadastrados</h5>
                            <p>{{ $totalUsuarios }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-book"></i>
                            <h5>Livros no Acervo</h5>
                            <p>{{ $totalLivros }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <i class="fas fa-handshake"></i>
                            <h5>Empréstimos Ativos</h5>
                            <p>{{ $totalEmprestimos }}</p>
                        </div>
                    </div>
                </div>
                
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Importando o JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
