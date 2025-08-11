<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'DressCode - Início')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Martel+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>
</head>
<body>

 <header>
    <div class="logo">
      <img src="" alt="DressCode Logo" />
    </div>
    <nav>
      <a href="{{ url('/Models/Feminino.php') }}">Feminino</a>
      <a href="#">Masculino</a>
      <a href="#">Infantil</a>
      <a href="#">Outros</a>
      <a href="{{ url('/Models/Cadastro.php') }}">Entrar/Cadastrar</a>
    </nav>
    <div class="search-box">
      <input type="text" placeholder="Buscar..." />
      <span class="search-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#5e2b2b" viewBox="0 0 24 24">
          <path d="M10 2a8 8 0 0 1 6.32 12.906l5.387 5.387-1.414 1.414-5.387-5.387A8 8 0 1 1 10 2zm0 2a6 6 0 1 0 0 12a6 6 0 0 0 0-12z"/>
        </svg>
      </span>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <footer>
    <div>
      <strong>Sobre</strong><br>
      “DressCode” é um projeto independente com o objetivo de divulgar a moda consciente e sustentável...
    </div>
    <div>
      <strong>Redes sociais</strong><br>
      @DressCodeInstagram<br>
      @DressCodeTikTok
    </div>
    <div>
      <strong>Ajuda</strong><br>
      FAQ<br>
      Como funciona<br>
      Suporte<br>
      Termos de uso
    </div>
    <div>
      <strong>Contato</strong><br>
      suportedresscode@dresscode.com<br>
      (11) 92348-9076
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>