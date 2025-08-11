<?php
require_once '../app/controllers/AuthController.php';

$auth = new AuthController();
$result = $auth->register();

$page_title = "Cadastro - DressCode";
$page_description = "Crie sua conta no DressCode e descubra moda sustentável em brechós online.";
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
            align-items: center;
            justify-content: center;
            padding: 1rem;
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
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: #5e2b2b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
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
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e1d8d8;
        }

        .links a {
            color: #5e2b2b;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 1s ease;
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">DC</div>
        <h1>Criar Conta</h1>

        <form action="cadastro.php" method="POST" id="formCadastro">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required minlength="6">
            </div>
            
            <button type="submit" class="btn" id="btnCadastrar">Criar Conta</button>
        </form>

        <div class="links">
            <a href="login.php">Já tenho uma conta</a>
        </div>
    </div>

    <?php if ($result): ?>
        <script>
            const tipo = '<?= $result["status"] ?>';
            const titulo = tipo === 'success' ? '✅ Sucesso!' : '❌ Erro!';
            const texto = <?= json_encode($result["message"]) ?>;

            Swal.fire({
                title: titulo,
                text: texto,
                icon: tipo === 'success' ? 'success' : 'error',
                confirmButtonColor: '#5e2b2b',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false
            });

            if (tipo === "success") {
                setTimeout(() => {
                    document.body.classList.add("fade-out");
                }, 3000);

                setTimeout(() => {
                    window.location.href = "login.php";
                }, 4000);
            }
        </script>
    <?php endif; ?>
</body>
</html>