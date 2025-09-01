<?php
session_start();
require_once '../app/controllers/FavoritosController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$favoritosController = new FavoritosController();
$favoritos = $favoritosController->listarFavoritos($_SESSION['user_id']);

$page_title = "Meus Favoritos - DressCode";
$page_description = "Seus produtos favoritos salvos na DressCode";
require_once '../includes/header.php';
?>

<main style="padding: 2rem 1rem; min-height: 70vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="color: #5e2b2b; margin-bottom: 2rem; text-align: center;">❤️ Meus Favoritos</h1>
        
        <?php if (empty($favoritos)): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h2 style="color: #666; margin-bottom: 1rem;">Nenhum produto favorito ainda</h2>
                <p style="color: #888; margin-bottom: 2rem;">Explore nossos produtos e adicione seus favoritos!</p>
                <a href="index.php" style="background: #5e2b2b; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 8px; font-weight: 600;">Explorar Produtos</a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($favoritos as $produto): ?>
                    <div class="produto-card">
                        <img src="assets/images/<?= htmlspecialchars($produto['imagem']) ?>" 
                             alt="<?= htmlspecialchars($produto['nome']) ?>" 
                             onclick="window.location.href='produto.php?id=<?= $produto['id'] ?>'">
                        
                        <div class="produto-info">
                            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            <div class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                            <div class="produto-detalhes">
                                <?= htmlspecialchars($produto['marca']) ?> • 
                                <?= htmlspecialchars($produto['tamanho']) ?> • 
                                <?= htmlspecialchars($produto['cor']) ?>
                            </div>
                            <span class="produto-condicao <?= strtolower($produto['condicao']) ?>">
                                <?= htmlspecialchars($produto['condicao']) ?>
                            </span>
                            
                            <button onclick="removerFavorito(<?= $produto['id'] ?>)" 
                                    style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; margin-top: 1rem; width: 100%;">
                                🗑️ Remover dos Favoritos
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function removerFavorito(produtoId) {
    Swal.fire({
        title: 'Remover dos favoritos?',
        text: 'Tem certeza que deseja remover este produto dos seus favoritos?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, remover',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('toggle-favorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    produto_id: produtoId,
                    action: 'remove'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Removido!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erro!', data.message, 'error');
                }
            });
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>