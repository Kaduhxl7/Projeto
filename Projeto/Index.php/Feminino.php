<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DressCode - Início</title>

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f2ef;
      margin: 0;
      padding: 0;
    }

    header {
       height: 70px;  
       background-color: #ffffff;
       padding: 1rem 2rem;
       display: flex;
       justify-content: space-between;
       align-items: center;
       border-bottom: 2px solid #e1d8d8;
       box-sizing: border-box;
       padding: 0 2rem;
    }

    .logo img {
      height: 260px; /* altura da logo, pode ajustar */
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

    .destaques {
      padding: 2rem 1rem;
      text-align: center;
    }

    .destaques h2 {
      color: #5e2b2b;
      margin-bottom: 1rem;
    }

    .swiper {
      width: 85%;
      max-width: 900px;
      margin: auto;
    }

    .swiper-slide img {
      width: 100%;
      height: 600px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .baseado-em-voce, .artigos {
      padding: 2rem 1rem;
      text-align: center;
    }

    .baseado-em-voce h2,
    .artigos h2 {
      color: #5e2b2b;
    }

    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
    }

    .card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 250px;
      text-align: center;
      overflow: hidden;
    }

    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card p {
      padding: 0.5rem;
      background-color: #5e2b2b;
      color: white;
      font-weight: bold;
    }

    .artigo {
      background-color: #b17979;
      color: white;
      padding: 1rem;
      border-radius: 10px;
      max-width: 400px;
    }

    .artigo button {
      background: #fff;
      color: #5e2b2b;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 1rem;
      transition: background 0.3s;
    }

    .artigo button:hover {
      background: #e6e6e6;
    }

    .artigos .cards {
      gap: 2rem;
    }

    footer {
      background: #fff;
      padding: 2rem;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      font-size: 0.9rem;
      color: #5e2b2b;
      border-top: 1px solid #ccc;
    }

    @media (max-width: 768px) {
      .cards {
        flex-direction: column;
        align-items: center;
      }
    }

    .search-box {
  background-color: #b19a9a;
  border-radius: 30px;
  padding: 0.4rem 1rem;
  display: flex;
  align-items: center;
  width: 200px;
  margin-left: 1rem;
}

.search-box input {
  border: none;
  outline: none;
  background: transparent;
  color: #fff;
  width: 100%;
  font-size: 0.9rem;
}

.search-box .search-icon {
  color: #5e2b2b;
  font-size: 1.2rem;
  margin-left: 0.5rem;
  cursor: pointer;
}



</style>
</head>
<body>

  <header>
  <div class="logo">
  <img src="logo.png" alt="DressCode Logo"/></div>
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

  <!-- Filtros laterais -->
  <aside style="width: 220px; background-color: #5e2b2b; color: white; padding: 1rem; font-family: 'Segoe UI', sans-serif; min-height: 100vh; box-sizing: border-box;">

    <details style="margin-bottom: 1rem;">
      <summary style="cursor: pointer;">Cor</summary>
      <ul style="list-style: none; padding-left: 1rem; margin-top: 0.5rem;">
        <li><input type="checkbox" id="cor1"> <label for="cor1">Vermelho</label></li>
        <li><input type="checkbox" id="cor2"> <label for="cor2">Preto</label></li>
        <li><input type="checkbox" id="cor3"> <label for="cor3">Branco</label></li>
      </ul>
    </details>

    <details style="margin-bottom: 1rem;">
      <summary style="cursor: pointer;">Tamanho</summary>
      <ul style="list-style: none; padding-left: 1rem; margin-top: 0.5rem;">
        <li><input type="checkbox" id="tam1"> <label for="tam1">P</label></li>
        <li><input type="checkbox" id="tam2"> <label for="tam2">M</label></li>
        <li><input type="checkbox" id="tam3"> <label for="tam3">G</label></li>
      </ul>
    </details>

    <details style="margin-bottom: 1rem;">
      <summary style="cursor: pointer;">Tipo</summary>
      <ul style="list-style: none; padding-left: 1rem; margin-top: 0.5rem;">
        <li><input type="checkbox" id="tipo1"> <label for="tipo1">Blusa</label></li>
        <li><input type="checkbox" id="tipo2"> <label for="tipo2">Calça</label></li>
        <li><input type="checkbox" id="tipo3"> <label for="tipo3">Saia</label></li>
      </ul>
    </details>

    <details style="margin-bottom: 1rem;">
      <summary style="cursor: pointer;">Marca</summary>
      <ul style="list-style: none; padding-left: 1rem; margin-top: 0.5rem;">
        <li><input type="checkbox" id="marca1"> <label for="marca1">Zara</label></li>
        <li><input type="checkbox" id="marca2"> <label for="marca2">C&A</label></li>
        <li><input type="checkbox" id="marca3"> <label for="marca3">Renner</label></li>
      </ul>
    </details>

  </aside>

  <!-- Conteúdo principal -->
  <main style="padding: 2rem; flex-grow: 1; overflow-y: auto;">
    <h2 style="color: #5e2b2b; margin-bottom: 1rem;"></h2>
    <div class="produto-container">
      <div class="produto-card">
        <img src="img/ergnu9itgnruedgregmei.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
      </div>
      <div class="produto-card">
        <img src="img/vretmunui9sf.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
      </div>
      <div class="produto-card">
        <img src="img/ff1b1c3ed2706fff44bbdce0441f394b3d564df3.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
       </div>
       <div class="produto-card">
        <img src="img/vvvv.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
      </div>
      <div class="produto-card">
        <img src="img/ttttt.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
      </div>
      <div class="produto-card">
        <img src="img/bbbbb.png">
        <h4 class="texto-cor1">Nome Produto</h4>
        <p>Desc. <br> Tipo <br> Tam</p>
      </div>
      
        <style>


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

.texto-cor1{
    color: #5e2b2b;
}
body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f5f2ef;
    margin: 0;
    padding: 0;
    overflow: hidden; /* ISSO REMOVE A BARRA DE ROLAGEM */
  }
        </style>
      </div>
      <!-- Repita conforme necessidade -->
    </div>
  </main>

</div>

</body>
</html>

