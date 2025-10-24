<?php
require_once '../app/controllers/AuthController.php';

$auth = new AuthController();
$result = $auth->login();

$page_title = "Login - DressCode";
$page_description = "Faça login na sua conta DressCode e explore moda sustentável.";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $page_description; ?>">
    <title><?php echo $page_title; ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f2ef 0%, #e8e0db 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-banner {
            background: linear-gradient(90deg, #664848 0%, #5e2b2b 100%);
            color: white;
            text-align: center;
            padding: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(94, 43, 43, 0.1);
            text-align: center;
        }

        .logo {
            width: 250px;
            height: auto;
            margin: 0 auto 1.5rem;
            display: block;
        }

        h1 {
            color: #5e2b2b;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5e2b2b;
            font-weight: 500;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e1d8d8;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #5e2b2b;
        }

        .btn {
            width: 100%;
            background: #5e2b2b;
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background: #4a2323;
        }

        .links {
            margin: 1.5rem 0;
            padding: 1rem 0;
            border-top: 1px solid #e1d8d8;
            border-bottom: 1px solid #e1d8d8;
        }

        .links a {
            color: #5e2b2b;
            text-decoration: none;
            font-weight: 500;
            margin: 0 0.5rem;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .footer {
            background: white;
            padding: 2rem 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            font-size: 0.875rem;
            color: #5e2b2b;
            border-top: 1px solid #e1d8d8;
        }

        .footer h4 {
            margin-bottom: 0.5rem;
            color: #5e2b2b;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 1s ease;
        }

        @media (max-width: 768px) {
            .footer {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="top-banner">Bem-vindo(a) de volta ao DressCode!</div>

    <div class="main-content">
        <div class="container">
            <img class="logo" src="assets/images/Logo.png" alt="DressCode Logo">

            <h1>Entrar na Conta</h1>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <button type="submit" class="btn" id="btnLogin">Entrar</button>
            </form>

            <div class="links">
                <a href="cadastro.php">Criar conta</a> |
                <a href="#" onclick="mostrarManutencao()">Esqueci a senha</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div>
            <h4>Sobre</h4>
            <p>DressCode é uma plataforma de brechós online focada em moda sustentável e consciente.</p>
        </div>
        <div>
            <h4>Redes Sociais</h4>
            <p>@DressCodeInstagram<br>@DressCodeTikTok<br>DressCodeLinkedin</p>
        </div>
        <div>
            <h4>Ajuda</h4>
            <p>FAQ<br>Suporte<br>Como Funciona<br>Termos de Uso</p>
        </div>
        <div>
            <h4>Contato</h4>
            <p>suportedresscode@dresscode.com<br>(11) 92348-9076</p>
        </div>
    </footer>

    <script>
        function mostrarManutencao() {
            Swal.fire({
                title: 'Funcionalidade em Desenvolvimento',
                text: 'Esta funcionalidade estará disponível em breve! 🚀',
                icon: 'info',
                confirmButtonText: 'Entendi',
                confirmButtonColor: '#5e2b2b',
                background: '#fffdfc',
                color: '#5e2b2b'
            });
        }
    </script>

    <?php if ($result): ?>
        <script>
            const status = '<?= $result["status"] ?>';
            const titulo = status === 'success' ? '✅ Login realizado!' : '❌ Erro no login';
            const texto = status === 'success' ? 'Bem-vindo(a) de volta!' : <?= json_encode($result["message"]) ?>;

            Swal.fire({
                title: titulo,
                text: texto,
                icon: status === 'success' ? 'success' : 'error',
                confirmButtonColor: '#5e2b2b',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });

            if (status === "success") {
                setTimeout(() => {
                    document.body.classList.add("fade-out");
                }, 2800);

                setTimeout(() => {
                    window.location.href = "index.php";
                }, 3800);
            }
        </script>
    <?php endif; ?>
</body>
</html>