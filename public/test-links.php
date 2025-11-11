<!DOCTYPE html>
<html>
<head>
    <title>Teste de Links - DressCode</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .link { display: block; margin: 1rem 0; padding: 1rem; background: #f5f5f5; border-radius: 8px; text-decoration: none; color: #333; }
        .link:hover { background: #e5e5e5; }
        .status { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔗 Teste de Links - DressCode</h1>
    
    <h2>Páginas Principais</h2>
    <a href="index.php" class="link">🏠 Página Inicial</a>
    <a href="login.php" class="link">🔐 Login</a>
    <a href="cadastro.php" class="link">📝 Cadastro</a>
    
    <h2>Categorias</h2>
    <a href="categoria.php?cat=feminino" class="link">👗 Feminino</a>
    <a href="categoria.php?cat=masculino" class="link">👔 Masculino</a>
    <a href="categoria.php?cat=infantil" class="link">👶 Infantil</a>
    <a href="categoria.php?cat=acessorios" class="link">👜 Acessórios</a>
    
    <h2>Funcionalidades</h2>
    <a href="busca.php?search=blusa" class="link">🔍 Busca por "blusa"</a>
    <a href="produto.php?id=1" class="link">📱 Produto ID 1</a>
    <a href="produto.php?id=2" class="link">📱 Produto ID 2</a>
    
    <div class="status">
        ✅ Banco: <?php 
        try {
            require_once '../app/config/database.php';
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->query("SELECT COUNT(*) as total FROM produtos");
            echo $stmt->fetch()['total'] . " produtos";
        } catch (Exception $e) {
            echo "❌ Erro: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>