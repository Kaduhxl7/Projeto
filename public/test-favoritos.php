<?php
session_start();

// Simular usuário logado para teste
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // ID do usuário de teste
}

echo "<h1>Teste de Favoritos</h1>";

try {
    require_once '../app/controllers/FavoritosController.php';
    
    $favoritosController = new FavoritosController();
    
    echo "<p>✅ Controller carregado com sucesso</p>";
    
    // Testar adicionar favorito
    $result = $favoritosController->adicionarFavorito(1, 1);
    echo "<p>Adicionar favorito: " . json_encode($result) . "</p>";
    
    // Testar verificar se é favorito
    $isFav = $favoritosController->isFavorito(1, 1);
    echo "<p>É favorito: " . ($isFav ? 'Sim' : 'Não') . "</p>";
    
    // Testar listar favoritos
    $favoritos = $favoritosController->listarFavoritos(1);
    echo "<p>Total de favoritos: " . count($favoritos) . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Erro: " . $e->getMessage() . "</p>";
}
?>