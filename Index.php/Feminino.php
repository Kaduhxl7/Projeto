<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DressCode - Início</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Martel+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f5f2ef;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    header {
      font-family: 'Martel Sans', sans-serif;
      height: 70px;
      background-color: #ffffff;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #e1d8d8;
      box-sizing: border-box;
    }

    .logo img {
      height: 260px;
      margin-top: 5px;
      width: auto;
      display: block;
    }

    nav a {
      margin: 0 1rem;
      color: #5e2b2b;
      text-decoration: none;
      font-weight: 500;
    }

    /* Barra de pesquisa ajustada */
    .search-box {
      background-color: #b19a9a;
      border-radius: 30px;
      padding: 0.63rem 1rem 0.75rem 1.5rem; /* menos padding à direita */
      display: flex;
      align-items: center;
      width: 260px; /* mais comprida */
      max-width: 100%;
      margin-left: 1rem;
    }

    .search-box input {
      border: none;
      outline: none;
      background: transparent;
      color: #fff;
      width: 100%;
      font-size: 1rem;
    }

    .search-box .search-icon {
      color: #5e2b2b;
      font-size: 1.2rem;
      margin-left: 0.5rem;
      cursor: pointer;
      margin-top: 2px; /* ou 1px, conforme necessário */
      display: flex;
      align-items: center;
    }

    aside {
      width: 220px;
      background-color: #5e2b2b;
      color: white;
      padding: 1rem;
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      box-sizing: border-box;
    }

    aside details {
      margin-bottom: 1rem;
    }

    aside summary {
      cursor: pointer;
    }

    aside ul {
      list-style: none;
      padding-left: 1rem;
      margin-top: 0.5rem;
    }

    main {
      padding: 2rem;
      flex-grow: 1;
      overflow-y: auto;
      font-family: 'Inter', sans-serif;
    }

    .produto-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      padding: 2rem;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      box-sizing: border-box;
    }

    .produto-card {
      background: white;
      border-radius: 12px;
      width: 240px;
      padding: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .produto-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .produto-card img {
      width: 100%;
      height: 260px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 0.75rem;
    }

    .produto-card h4 {
      margin: 0.5rem 0 0.3rem;
      color: #5e2b2b;
      font-size: 1.1rem;
    }

    .produto-card p {
      color: #333;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    .texto-cor1 {
      color: #5e2b2b;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">
      <img src="logo.png" alt="DressCode Logo" />
    </div>
    <nav>
      <a href="Feminino.php">Feminino</a>
      <a href="#">Masculino</a>
      <a href="#">Infantil</a>
      <a href="#">Outros</a>
      <a href="Tela_login.php">Entrar/Cadastrar</a>
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

  <div style="display: flex; height: 100vh; margin: 0;">
    <aside>
      <details>
        <summary>Cor</summary>
        <ul>
          <li><input type="checkbox" id="cor1"> <label for="cor1">Vermelho</label></li>
          <li><input type="checkbox" id="cor2"> <label for="cor2">Preto</label></li>
          <li><input type="checkbox" id="cor3"> <label for="cor3">Branco</label></li>
        </ul>
      </details>

      <details>
        <summary>Tamanho</summary>
        <ul>
          <li><input type="checkbox" id="tam1"> <label for="tam1">P</label></li>
          <li><input type="checkbox" id="tam2"> <label for="tam2">M</label></li>
          <li><input type="checkbox" id="tam3"> <label for="tam3">G</label></li>
        </ul>
      </details>

      <details>
        <summary>Tipo</summary>
        <ul>
          <li><input type="checkbox" id="tipo1"> <label for="tipo1">Blusa</label></li>
          <li><input type="checkbox" id="tipo2"> <label for="tipo2">Calça</label></li>
          <li><input type="checkbox" id="tipo3"> <label for="tipo3">Saia</label></li>
        </ul>
      </details>

      <details>
        <summary>Marca</summary>
        <ul>
          <li><input type="checkbox" id="marca1"> <label for="marca1">Zara</label></li>
          <li><input type="checkbox" id="marca2"> <label for="marca2">C&A</label></li>
          <li><input type="checkbox" id="marca3"> <label for="marca3">Renner</label></li>
        </ul>
      </details>
    </aside>

    <main>
      
      <div class="produto-container">
        <div class="produto-card">
          <img src="img/ergnu9itgnruedgregmei.png" alt="Produto 1">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
        <div class="produto-card">
          <img src="img/vretmunui9sf.png" alt="Produto 2">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
        <div class="produto-card">
          <img src="img/ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png" alt="Produto 3">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
        <div class="produto-card">
          <img src="img/vvvv.png" alt="Produto 4">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
        <div class="produto-card">
          <img src="img/ttttt.png" alt="Produto 5">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
        <div class="produto-card">
          <img src="img/bbbbb.png" alt="Produto 6">
          <h4 class="texto-cor1">Nome Produto</h4>
          <p>Desc. <br> Tipo <br> Tam</p>
        </div>
      </div>
    </main>
  </div>

</body>
</html>