<?php
/**
 * Exemplo de uso dos Padrões GoF implementados
 * 
 * Este arquivo demonstra como usar os padrões implementados no sistema.
 * Execute este arquivo para ver os padrões em ação.
 */

require_once __DIR__ . '/../app/config/DatabaseConnection.php';
require_once __DIR__ . '/../app/factories/ProductFactory.php';
require_once __DIR__ . '/../app/observers/ProductPublisher.php';
require_once __DIR__ . '/../app/observers/NotificationObserver.php';
require_once __DIR__ . '/../app/strategies/SearchContext.php';

echo "<h1>🎯 Demonstração dos Padrões GoF</h1>";

// =============================================================================
// 1. SINGLETON PATTERN - Conexão com Banco de Dados
// =============================================================================
echo "<h2>🔧 1. Singleton Pattern - Conexão com Banco</h2>";

try {
    // Primeira chamada - cria a instância
    $db1 = DatabaseConnection::getInstance();
    echo "✅ Primeira conexão criada<br>";
    
    // Segunda chamada - retorna a mesma instância
    $db2 = DatabaseConnection::getInstance();
    echo "✅ Segunda conexão obtida<br>";
    
    // Verificar se são a mesma instância
    if ($db1 === $db2) {
        echo "🎉 <strong>Singleton funcionando!</strong> Ambas as variáveis referenciam a mesma instância.<br>";
    }
    
    // Informações da conexão
    $info = DatabaseConnection::getConnectionInfo();
    echo "📊 Informações da conexão:<br>";
    echo "- Host: {$info['host']}<br>";
    echo "- Database: {$info['database']}<br>";
    echo "- Conectado: " . ($info['connected'] ? 'Sim' : 'Não') . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// =============================================================================
// 2. FACTORY METHOD PATTERN - Criação de Produtos
// =============================================================================
echo "<h2>🏭 2. Factory Method Pattern - Criação de Produtos</h2>";

// Dados de exemplo para diferentes tipos de produtos
$produtoNovo = [
    'nome' => 'Camiseta Nike Original',
    'descricao' => 'Camiseta esportiva nova com etiqueta',
    'preco' => 89.90,
    'condicao' => 'novo',
    'marca' => 'Nike',
    'cor' => 'Azul',
    'tamanho' => 'M'
];

$produtoUsado = [
    'nome' => 'Jeans Levi\'s Vintage',
    'descricao' => 'Calça jeans em ótimo estado',
    'preco' => 120.00,
    'condicao' => 'usado',
    'marca' => 'Levi\'s',
    'cor' => 'Azul',
    'tamanho' => '38'
];

$produtoPromocional = [
    'nome' => 'Vestido Floral Promocional',
    'descricao' => 'Vestido lindo em promoção especial',
    'preco' => 150.00,
    'condicao' => 'promocional',
    'desconto' => 30,
    'marca' => 'Zara',
    'cor' => 'Floral',
    'tamanho' => 'P'
];

try {
    // Criar produtos usando Factory
    $produto1 = ProductFactory::createProduct($produtoNovo);
    $produto2 = ProductFactory::createProduct($produtoUsado);
    $produto3 = ProductFactory::createProduct($produtoPromocional);
    
    echo "✅ Produtos criados com sucesso!<br><br>";
    
    // Mostrar informações dos produtos
    echo "<strong>Produto Novo:</strong><br>";
    echo "- Nome: {$produto1->nome}<br>";
    echo "- Preço: R$ {$produto1->preco}<br>";
    echo "- Garantia: " . ($produto1->hasGarantia() ? 'Sim' : 'Não') . "<br>";
    echo "- Prioridade: {$produto1->getPrioridade()}<br><br>";
    
    echo "<strong>Produto Usado:</strong><br>";
    echo "- Nome: {$produto2->nome}<br>";
    echo "- Preço original: R$ {$produto2->preco}<br>";
    echo "- Preço com desconto: R$ " . number_format($produto2->getPrecoComDesconto(), 2) . "<br>";
    echo "- Desconto: {$produto2->getDesconto()}%<br><br>";
    
    echo "<strong>Produto Promocional:</strong><br>";
    echo "- Nome: {$produto3->nome}<br>";
    echo "- Preço original: R$ {$produto3->preco}<br>";
    echo "- Preço com desconto: R$ " . number_format($produto3->getPrecoComDesconto(), 2) . "<br>";
    echo "- Desconto: {$produto3->getDesconto()}%<br>";
    echo "- É promocional: " . ($produto3->isPromocional() ? 'Sim' : 'Não') . "<br>";
    
    echo "<br>🎉 <strong>Factory Method funcionando!</strong> Diferentes tipos de produtos criados com comportamentos específicos.<br>";
    
} catch (Exception $e) {
    echo "❌ Erro ao criar produtos: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// =============================================================================
// 3. OBSERVER PATTERN - Sistema de Notificações
// =============================================================================
echo "<h2>👁️ 3. Observer Pattern - Sistema de Notificações</h2>";

try {
    // Obter instância do publisher (também usa Singleton)
    $publisher = ProductPublisher::getInstance();
    
    // Adicionar observador de notificações
    $notificationObserver = new NotificationObserver();
    $publisher->attach($notificationObserver);
    
    echo "✅ Observer de notificações registrado<br>";
    
    // Simular eventos
    echo "<br><strong>Simulando eventos:</strong><br>";
    
    // Evento: Novo produto
    echo "📦 Disparando evento de novo produto...<br>";
    $publisher->notifyNewProduct(1, "Camiseta Adidas Exclusiva", 1);
    
    // Evento: Promoção
    echo "💸 Disparando evento de promoção...<br>";
    $publisher->notifyPromotion(
        "Super Desconto de Verão", 
        "Até 50% de desconto em roupas de verão!", 
        1, 
        "Brechó da Maria"
    );
    
    // Evento: Atualização de brechó
    echo "🏪 Disparando evento de atualização de brechó...<br>";
    $publisher->notifyStoreUpdate(1, "Brechó da Maria");
    
    // Mostrar observadores registrados
    $observers = $publisher->getObservers();
    echo "<br>📋 Observadores registrados: " . implode(', ', $observers) . "<br>";
    
    echo "<br>🎉 <strong>Observer Pattern funcionando!</strong> Eventos disparados e observadores notificados automaticamente.<br>";
    
} catch (Exception $e) {
    echo "❌ Erro no sistema de observadores: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// =============================================================================
// 4. STRATEGY PATTERN - Algoritmos de Busca
// =============================================================================
echo "<h2>🎯 4. Strategy Pattern - Algoritmos de Busca</h2>";

try {
    $searchContext = new SearchContext();
    
    echo "<strong>Estratégias disponíveis:</strong><br>";
    $strategies = SearchContext::getAvailableStrategies();
    foreach ($strategies as $key => $strategy) {
        echo "- {$strategy['name']}: {$strategy['description']}<br>";
    }
    
    echo "<br><strong>Testando determinação automática de estratégias:</strong><br>";
    
    // Teste 1: Busca por localização
    $filtrosLocalizacao = [
        'latitude' => -23.5505,
        'longitude' => -46.6333,
        'radius' => 10,
        'search' => 'brechó'
    ];
    
    $estrategia1 = $searchContext->determineStrategy($filtrosLocalizacao);
    echo "🌍 Filtros com coordenadas → Estratégia: {$estrategia1->getName()}<br>";
    
    // Teste 2: Busca por preço
    $filtrosPreco = [
        'preco_min' => 50,
        'preco_max' => 200,
        'apenas_ofertas' => true
    ];
    
    $estrategia2 = $searchContext->determineStrategy($filtrosPreco);
    echo "💰 Filtros de preço → Estratégia: {$estrategia2->getName()}<br>";
    
    // Teste 3: Busca por categoria
    $filtrosCategoria = [
        'categoria' => 'roupas-femininas',
        'cor' => 'azul',
        'tamanho' => 'M'
    ];
    
    $estrategia3 = $searchContext->determineStrategy($filtrosCategoria);
    echo "📂 Filtros de categoria → Estratégia: {$estrategia3->getName()}<br>";
    
    // Mostrar informações da estratégia atual
    $searchContext->setStrategy($estrategia1);
    $info = $searchContext->getCurrentStrategyInfo();
    echo "<br>📊 Estratégia atual: {$info['name']} ({$info['class']})<br>";
    
    echo "<br>🎉 <strong>Strategy Pattern funcionando!</strong> Diferentes algoritmos selecionados automaticamente baseados nos filtros.<br>";
    
} catch (Exception $e) {
    echo "❌ Erro no sistema de estratégias: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// =============================================================================
// INTEGRAÇÃO DOS PADRÕES
// =============================================================================
echo "<h2>🔄 Integração dos Padrões</h2>";

echo "<strong>Demonstração de como os padrões trabalham juntos:</strong><br><br>";

try {
    // 1. Usar Singleton para conexão
    $db = DatabaseConnection::getInstance();
    echo "1️⃣ Singleton: Conexão única obtida<br>";
    
    // 2. Usar Factory para criar produto
    $novoProduto = ProductFactory::createProduct([
        'nome' => 'Produto Integração',
        'preco' => 99.99,
        'condicao' => 'promocional',
        'desconto' => 25
    ]);
    echo "2️⃣ Factory: Produto promocional criado com 25% de desconto<br>";
    
    // 3. Usar Observer para notificar sobre o produto
    $publisher = ProductPublisher::getInstance();
    $publisher->notifyNewProduct(999, $novoProduto->nome, 1);
    echo "3️⃣ Observer: Notificações enviadas sobre o novo produto<br>";
    
    // 4. Usar Strategy para buscar produtos similares
    $searchContext = new SearchContext();
    $filtros = ['condicao' => 'promocional', 'preco_max' => 150];
    $estrategia = $searchContext->determineStrategy($filtros);
    echo "4️⃣ Strategy: Estratégia '{$estrategia->getName()}' selecionada para buscar produtos similares<br>";
    
    echo "<br>🎉 <strong>Integração completa!</strong> Todos os padrões trabalhando em harmonia.<br>";
    
} catch (Exception $e) {
    echo "❌ Erro na integração: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// =============================================================================
// RESUMO FINAL
// =============================================================================
echo "<h2>📋 Resumo Final</h2>";

echo "<div style='background-color: #f0f8ff; padding: 15px; border-radius: 5px;'>";
echo "<strong>✅ Padrões GoF implementados com sucesso:</strong><br><br>";
echo "🔧 <strong>Singleton:</strong> Conexão única com banco de dados<br>";
echo "🏭 <strong>Factory Method:</strong> Criação tipada de produtos<br>";
echo "👁️ <strong>Observer:</strong> Sistema de notificações desacoplado<br>";
echo "🎯 <strong>Strategy:</strong> Algoritmos de busca intercambiáveis<br><br>";
echo "<strong>🚀 Benefícios obtidos:</strong><br>";
echo "- Código mais limpo e organizado<br>";
echo "- Melhor performance e uso de recursos<br>";
echo "- Maior flexibilidade e extensibilidade<br>";
echo "- Facilita manutenção e testes<br>";
echo "</div>";

echo "<br><p><em>Para mais detalhes, consulte a documentação em: <strong>docs/padroes_projeto.md</strong></em></p>";
?>