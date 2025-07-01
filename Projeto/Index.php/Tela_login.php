<?php
session_start();

$erro = "";

// Conexão PDO
$host = 'localhost';
$db   = 'sistema_cadastro';
$user = 'root';
$pass = '1234';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $erro = "Erro na conexão com o banco de dados.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION["usuario"] = $usuario["email"];
        $login_status = "sucesso";
    } else {
        $erro = "E-mail ou senha inválidos.";
        $login_status = "erro";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - DressCode</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f2ef;
      margin: 0;
      padding: 0;
      transition: opacity 1s ease;
    }

    .top-banner {
      background: #664848;
      color: white;
      text-align: center;
      padding: 0.5rem;
      font-weight: bold;
    }

    .container {
      max-width: 600px;
      margin: 2rem auto;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .logo {
      width: 250px;
      height: auto;
      display: block;
      margin: -95px auto 0 auto;
    }

    .separador {
      border: none;
      height: 1px;
      background-color: black;
      margin: -100px auto 20px auto;
      width: 85%;
    }

    h1 { color: #5e2b2b; }

    input[type="email"], input[type="password"] {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border-radius: 20px;
      border: none;
      background-color: #a97474;
      color: white;
      font-size: 1rem;
    }

    input::placeholder { color: #f5f2ef; }

    button {
      background: #5e2b2b;
      color: white;
      border: none;
      padding: 0.7rem 2rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .links {
      margin-bottom: 1rem;
      color: #5e2b2b;
      font-size: 0.9rem;
    }

    .links a {
      text-decoration: none;
      color: #5e2b2b;
      margin: 0 5px;
    }

    .footer {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      padding: 2rem;
      border-top: 1px solid #ccc;
      font-size: 0.85rem;
      color: #5e2b2b;
    }

    .footer div {
      max-width: 200px;
    }

    .fade-out {
      opacity: 0;
    }
  </style>
</head>
<body>
  <div class="top-banner">Bem-vindo(a) de volta!</div>

  <div class="container">
    <img class="logo" src="logo.png" alt="DressCode Logo">
    <hr class="separador">

    <h1>Login</h1>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="E-mail ou celular" required><br>
      <input type="password" name="senha" placeholder="Senha" required><br>
      <div class="links">
        <a href="Cadastro.php">Não tenho uma conta</a> |
        <a href="Cadastro.php">Esqueci a senha</a>
      </div>
      <button type="submit" id="btnLogin">Entrar</button>
    </form>
  </div>

  <footer class="footer">
    <div>
      <h4>Sobre</h4>
      <p>“DressCode” é um projeto independente com o objetivo de divulgar a moda consciente e sustentável...</p>
    </div>
    <div>
      <h4>Nossas redes sociais</h4>
      <p>@DressCodeInstagram<br>DressCodeLinkedin<br>@DressCodeTikTok</p>
    </div>
    <div>
      <h4>Ajuda</h4>
      <p>FAQ<br>Configurações<br>Suporte<br>Quero Vender<br>Cadastro</p>
    </div>
    <div>
      <h4>Contato</h4>
      <p>suportedresscode@dresscode.com<br>(31) 91234-5678</p>
    </div>
  </footer>

  <!-- Sons -->
  <audio id="successSound" src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_4c9ad96379.mp3?filename=correct-2-46134.mp3"></audio>
  <audio id="errorSound" src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_5a5b0bb9db.mp3?filename=error-2-30384.mp3"></audio>
  <audio id="clickSound" src="https://cdn.pixabay.com/download/audio/2021/09/20/audio_4389ce8df3.mp3?filename=click-21156.mp3"></audio>

  <script>
    document.getElementById("btnLogin").addEventListener("click", () => {
      document.getElementById("clickSound").play();
    });
  </script>

  <?php if (!empty($login_status)): ?>
    <script>
      const status = '<?= $login_status ?>';
      const som = status === 'sucesso' ? document.getElementById('successSound') : document.getElementById('errorSound');
      const titulo = status === 'sucesso' ? '✅ Login realizado!' : '❌ Erro no login';
      const texto = status === 'sucesso' ? 'Bem-vindo(a) de volta!' : 'E-mail ou senha inválidos.';

      som.play();

      Swal.fire({
        title: titulo,
        text: texto,
        icon: status,
        confirmButtonColor: '#5e2b2b',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
      });

      if (status === "sucesso") {
        setTimeout(() => {
          document.body.classList.add("fade-out");
        }, 2800);

        setTimeout(() => {
          window.location.href = "Tela_Inicial.html"; // Página principal após login
        }, 3800);
      }
    </script>
  <?php endif; ?>
</body>
</html>