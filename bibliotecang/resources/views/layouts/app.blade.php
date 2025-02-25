<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gerenciamento da Biblioteca</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  
  <!-- CSS customizado -->
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <style>
    /* Variáveis de cores para facilitar a customização */
    :root {
      --primary-color: #0056b3;         /* Azul escuro */
      --secondary-color: #007bff;       /* Azul vibrante */
      --accent-color: #f1c40f;          /* Amarelo acentuado */
      --background-color: #f8f9fa;       /* Cinza bem claro */
      --sidebar-bg: #ffffff;            /* Fundo da sidebar */
      /* O header agora terá a tonalidade inspirada: */
      --header-bg: #29448E;             /* Azul escuro robusto */
      --header-text-color: #ffffff;
      --text-color: #333333;
      --link-color: #6c757d;
    }

    /* Fonte global e background geral */
    body {
      font-family: 'Roboto', sans-serif;
      background: var(--background-color);
      color: var(--text-color);
      margin: 0;
      padding: 0;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      background: var(--sidebar-bg);
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      padding: 20px 0;
      top: 0;
      left: 0;
      z-index: 1100;
      transition: transform 0.3s ease;
    }
    .sidebar h4 {
      color: var(--primary-color);
      margin-bottom: 30px;
      font-weight: 700;
      text-align: center;
    }
    .sidebar a {
      color: var(--link-color);
      text-decoration: none;
      padding: 12px 20px;
      display: block;
      transition: background 0.3s, color 0.3s;
      font-size: 16px;
    }
    .sidebar a:hover {
      background: var(--secondary-color);
      color: #fff;
    }

    .header- {
      background-color: var(--header-bg);
      color: var(--header-text-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      font-family: sans-serif;
      position: fixed;
      top: 0;
      left: 250px;
      width: calc(100% - 250px);
      z-index: 1050;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .header- .logo {
      display: flex;
      align-items: center;
    }
    .header- .logo img {
      height: 60px;
      margin-right: 15px;
    }
    .header- .logo p {
      font-size: 0.8em;
    }
    .header- .texto-principal h1 {
      font-size: 1.8em;
      font-weight: bold;
      margin: 0;
    }

    /* Conteúdo principal */
    .content {
      margin-top: 90px; 
      margin-left: 250px;
      padding: 20px;
      background: var(--background-color);
      min-height: calc(100vh - 90px);
      transition: margin 0.3s ease;
    }

    /* Responsividade para telas menores */
    @media (max-width: 767px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: none;
      }
      .header- {
        left: 0;
        width: 100%;
      }
      .content {
        margin-left: 0;
      }
      /* Exibe botão para togglar a sidebar */
      .sidebar-toggle {
        display: block;
      }
    }

    /* Botão Toggle para sidebar (mobile) */
    .sidebar-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1200;
      background: var(--primary-color);
      border: none;
      color: #fff;
      padding: 10px 15px;
      border-radius: 4px;
      display: none;
      cursor: pointer;
    }

    /* Transições e interações */
    .card, .btn, .owl-carousel .item img {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover, .owl-carousel .item img:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
  </style>
  
  <!-- jQuery Mask Plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mask-plugin/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
  <!-- Botão Toggle para dispositivos móveis -->
  <button class="sidebar-toggle d-md-none" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
    <i class="fas fa-bars"></i>
  </button>
  
  <div class="d-flex flex-column flex-md-row">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <h4>BIBLIOTECÁRIO</h4>
      <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
      <a href="{{ route('livros.index') }}"><i class="fas fa-book"></i> Livros</a>
      <a href="{{ route('usuarios.index') }}"><i class="fas fa-users"></i> Usuários</a>
      <a href="{{ route('emprestimos.index') }}"><i class="fas fa-handshake"></i> Empréstimos</a>
      <a href="{{ route('relatorio.index') }}"><i class="fas fa-chart-bar"></i> Relatórios</a>
    </div>
    
    <!-- Conteúdo Principal -->
    <div class="content w-100">
      <div class="header-">
        <div class="texto-principal">
          <h1>Sistema Bibliotecário</h1>
        </div>
      </div>
      
      <div class="container mt-4">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery (necessário para Owl Carousel) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  @yield('scripts')
</body>
</html>
