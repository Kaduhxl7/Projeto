<?php
// Inicia a sessão para armazenar mensagens (se quiser usar sessão futuramente)
session_start();



$mensagem = "";
$tipoMensagem = "";

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
    $mensagem = "Erro na conexão com o banco de dados.";
    $tipoMensagem = "erro";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido.";
        $tipoMensagem = "erro";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetchColumn() > 0) {
            $mensagem = "Este e-mail já está cadastrado.";
            $tipoMensagem = "erro";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha) VALUES (:email, :senha)");
                $stmt->execute([
                    ':email' => $email,
                    ':senha' => $senhaHash
                ]);
                $mensagem = "Cadastro realizado com sucesso!";
                $tipoMensagem = "sucesso";
            } catch (PDOException $e) {
                $mensagem = "Erro ao cadastrar. Tente novamente.";
                $tipoMensagem = "erro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - DressCode</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f2ef;
      margin: 0;
      padding: 0;
      transition: opacity 1s ease;
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

    button {
      background: #5e2b2b;
      color: white;
      border: none;
      padding: 0.7rem 2rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    /* Efeito de fade-out */
    .fade-out {
      opacity: 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Crie sua conta</h1>

    <form action="Cadastro.php" method="POST" id="formCadastro">
      <input type="email" name="email" placeholder="E-mail ou celular" required><br>
      <input type="password" name="senha" placeholder="Crie uma senha" required><br>
      <button type="submit" id="btnCadastrar">Cadastrar</button>
    </form>
  </div>

  <!-- Sons -->
  <audio id="successSound" src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_4c9ad96379.mp3?filename=correct-2-46134.mp3"></audio>
  <audio id="errorSound" src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_5a5b0bb9db.mp3?filename=error-2-30384.mp3"></audio>
  <audio id="clickSound" src="https://cdn.pixabay.com/download/audio/2021/09/20/audio_4389ce8df3.mp3?filename=click-21156.mp3"></audio>

  <script>
    // Som de clique no botão
    const btn = document.getElementById("btnCadastrar");
    btn.addEventListener("click", () => {
      document.getElementById("clickSound").play();
    });
  </script>

  <?php if (!empty($mensagem)): ?>
    <script>
      const tipo = '<?= $tipoMensagem ?>';
      const titulo = tipo === 'sucesso' ? '✅ Sucesso!' : '❌ Erro!';
      const texto = "<?= $mensagem ?>";
      const som = tipo === 'sucesso' ? document.getElementById('successSound') : document.getElementById('errorSound');

      som.play();

      Swal.fire({
        title: titulo,
        text: texto,
        icon: tipo,
        confirmButtonColor: '#5e2b2b',
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false
      });

      if (tipo === "sucesso") {
        setTimeout(() => {
          document.body.classList.add("fade-out");
        }, 3000);

        setTimeout(() => {
          window.location.href = "Tela_login.php";
        }, 4000);
      }
    </script>
  <?php endif; ?>
</body>
</html>