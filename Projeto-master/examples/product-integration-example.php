<?php
/**
 * EXEMPLO DE INTEGRAÇÃO - Como integrar notificações ao cadastrar produtos
 * 
 * Este arquivo mostra como integrar o sistema de notificações
 * quando um novo produto é cadastrado no sistema.
 */

require_once __DIR__ . '/../app/helpers/NotificationIntegration.php';
require_once __DIR__ . '/../app/config/database.php';

// Exemplo de função para cadastrar produto com notificação automática
function cadastrarProdutoComNotificacao($dados_produto) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // 1. Inserir produto no banco (usando colunas existentes)
        $sql = "INSERT INTO produtos (nome, descricao, preco, cor, tamanho, condicao, imagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $dados_produto['nome'],
            $dados_produto['descricao'],
            $dados_produto['preco'],
            $dados_produto['cor'],
            $dados_produto['tamanho'],
            $dados_produto['condicao'],
            $dados_produto['imagem']
        ]);
        
        if ($result) {
            $produto_id = $db->lastInsertId();
            
            // 2. Enviar notificação automaticamente
            $notificacoes_enviadas = notify_new_product(
                $produto_id, 
                $dados_produto['nome'], 
                null // brecho_id opcional
            );
            
            return [
                'success' => true,
                'produto_id' => $produto_id,
                'notificacoes_enviadas' => $notificacoes_enviadas,
                'message' => "Produto cadastrado com sucesso! $notificacoes_enviadas usuários foram notificados."
            ];
        }
        
        return ['success' => false, 'message' => 'Erro ao cadastrar produto'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro: ' . $e->getMessage()];
    }
}

// Exemplo de uso em um formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_produto'])) {
    $dados_produto = [
        'nome' => $_POST['nome'] ?? '',
        'descricao' => $_POST['descricao'] ?? '',
        'preco' => floatval($_POST['preco'] ?? 0),
        'cor' => $_POST['cor'] ?? '',
        'tamanho' => $_POST['tamanho'] ?? '',
        'condicao' => $_POST['condicao'] ?? '',
        'imagem' => 'exemplo.jpg'
    ];
    
    $resultado = cadastrarProdutoComNotificacao($dados_produto);
    
    if ($resultado['success']) {
        echo "<div class='alert alert-success'>{$resultado['message']}</div>";
    } else {
        echo "<div class='alert alert-error'>{$resultado['message']}</div>";
    }
}

// Exemplo de integração em AJAX
if (isset($_GET['ajax_example'])) {
    header('Content-Type: application/json');
    
    // Simular cadastro via AJAX
    $produto_exemplo = [
        'nome' => 'Camiseta Vintage AJAX',
        'descricao' => 'Produto cadastrado via AJAX com notificação automática',
        'preco' => 45.90,
        'cor' => 'azul',
        'tamanho' => 'M',
        'condicao' => 'seminovo',
        'imagem' => 'exemplo.jpg'
    ];
    
    $resultado = cadastrarProdutoComNotificacao($produto_exemplo);
    echo json_encode($resultado);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplo de Integração - Notificações</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #5e2b2b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #4a2222; }
        .code-example { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        pre { overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔔 Exemplo de Integração - Sistema de Notificações</h1>
    
    <div class="alert alert-success">
        <strong>✅ Sistema Integrado!</strong><br>
        Este exemplo mostra como integrar automaticamente o sistema de notificações 
        quando novos produtos são cadastrados.
    </div>
    
    <h2>📝 Formulário de Exemplo</h2>
    <form method="POST">
        <div class="form-group">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <option value="feminino">Feminino</option>
                <option value="masculino">Masculino</option>
                <option value="infantil">Infantil</option>
                <option value="acessorios">Acessórios</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="cor">Cor:</label>
            <input type="text" id="cor" name="cor">
        </div>
        
        <div class="form-group">
            <label for="tamanho">Tamanho:</label>
            <select id="tamanho" name="tamanho">
                <option value="">Selecione...</option>
                <option value="PP">PP</option>
                <option value="P">P</option>
                <option value="M">M</option>
                <option value="G">G</option>
                <option value="GG">GG</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="condicao">Condição:</label>
            <select id="condicao" name="condicao" required>
                <option value="">Selecione...</option>
                <option value="novo">Novo</option>
                <option value="seminovo">Seminovo</option>
                <option value="usado">Usado</option>
                <option value="vintage">Vintage</option>
            </select>
        </div>
        
        <button type="submit" name="cadastrar_produto">
            📦 Cadastrar Produto (com notificação automática)
        </button>
    </form>
    
    <h2>🚀 Teste AJAX</h2>
    <button onclick="testarAjax()">🧪 Testar Cadastro via AJAX</button>
    <div id="ajax-result"></div>
    
    <h2>💻 Como Integrar no Seu Código</h2>
    
    <div class="code-example">
        <h3>1. Incluir o arquivo de integração:</h3>
        <pre><code>require_once __DIR__ . '/app/helpers/NotificationIntegration.php';</code></pre>
    </div>
    
    <div class="code-example">
        <h3>2. Chamar a função após cadastrar produto:</h3>
        <pre><code>// Após inserir produto no banco
$produto_id = $db->lastInsertId();

// Enviar notificação automaticamente
$notificacoes_enviadas = notify_new_product(
    $produto_id, 
    $nome_produto, 
    $brecho_id  // opcional
);

echo "Produto cadastrado! $notificacoes_enviadas usuários notificados.";</code></pre>
    </div>
    
    <div class="code-example">
        <h3>3. Para promoções:</h3>
        <pre><code>// Enviar notificação de promoção
$notificacoes_enviadas = notify_promotion(
    "Desconto de 30%",           // título
    "Válido até domingo!",       // descrição  
    $brecho_id,                  // opcional
    "Nome do Brechó"             // opcional
);</code></pre>
    </div>
    
    <div class="code-example">
        <h3>4. Para atualizações do brechó:</h3>
        <pre><code>// Notificar atualização do brechó
$notificacoes_enviadas = notify_store_update(
    $brecho_id,
    "Nome do Brechó"
);</code></pre>
    </div>
    
    <h2>📋 Funcionalidades Implementadas</h2>
    <ul>
        <li>✅ <strong>Notificações automáticas</strong> - Enviadas quando produtos são cadastrados</li>
        <li>✅ <strong>Sistema multilíngue</strong> - Suporte a PT, EN, ES, FR</li>
        <li>✅ <strong>Interface moderna</strong> - Design responsivo e elegante</li>
        <li>✅ <strong>Envio manual</strong> - Donos de brechó podem enviar promoções</li>
        <li>✅ <strong>Marcação de leitura</strong> - Individual e em lote</li>
        <li>✅ <strong>Tipos diferenciados</strong> - Ícones e cores por tipo de notificação</li>
        <li>✅ <strong>Performance otimizada</strong> - Índices e limpeza automática</li>
        <li>✅ <strong>Integração simples</strong> - Apenas uma linha de código</li>
    </ul>
    
    <div class="alert alert-success">
        <strong>🎉 Sistema Pronto!</strong><br>
        O sistema de notificações está completamente funcional e integrado. 
        <a href="../public/notifications.php" target="_blank">Clique aqui para ver as notificações</a>
    </div>

    <script>
    function testarAjax() {
        const resultDiv = document.getElementById('ajax-result');
        resultDiv.innerHTML = '<p>⏳ Testando...</p>';
        
        fetch('?ajax_example=1')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <strong>✅ Sucesso!</strong><br>
                            ${data.message}<br>
                            <small>Produto ID: ${data.produto_id}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-error">
                            <strong>❌ Erro:</strong> ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <div class="alert alert-error">
                        <strong>❌ Erro:</strong> ${error.message}
                    </div>
                `;
            });
    }
    </script>
</body>
</html>