<?php
$page_title = __('nav.favorites') . " - " . __('site.title');
$page_description = __('favorites.description');
require_once __DIR__ . '/../../../includes/header.php';
?>

<main style="padding: 2rem 1rem; min-height: 70vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="color: #5e2b2b; margin-bottom: 2rem; text-align: center;"><img src="assets/Ícones/coracao.png" alt="Avatar" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;"> <?php echo __('favorites.title'); ?></h1>
        
        <?php if (empty($data['favoritos'])): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h2 style="color: #666; margin-bottom: 1rem;"><?php echo __('favorites.no_items'); ?></h2>
                <p style="color: #888; margin-bottom: 2rem;"><?php echo __('favorites.explore_message'); ?></p>
                <a href="index.php" style="background: #5e2b2b; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 8px; font-weight: 600;"><?php echo __('favorites.explore_products'); ?></a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($data['favoritos'] as $produto): ?>
                    <div class="produto-card">
                        <img src="assets/images/<?= htmlspecialchars($produto['imagem']) ?>" 
                             alt="<?= htmlspecialchars($produto['nome']) ?>" 
                             onclick="window.location.href='produto.php?id=<?= $produto['id'] ?>'">
                        
                        <div class="produto-info">
                            <h3><?= TranslationHelper::translateDynamic($produto['nome'], 'product_name') ?></h3>
                            <div class="produto-preco"><?= TranslationHelper::formatPrice($produto['preco']) ?></div>
                            <div class="produto-detalhes">
                                <?= htmlspecialchars($produto['marca']) ?> • 
                                <?= TranslationHelper::translateSize($produto['tamanho']) ?> • 
                                <?= TranslationHelper::translateColor($produto['cor']) ?>
                            </div>
                            <span class="produto-condicao <?= strtolower($produto['condicao']) ?>">
                                <?= __('conditions.' . strtolower($produto['condicao'])) ?>
                            </span>
                            
                            <button onclick="removerFavorito(<?= $produto['id'] ?>)" 
                                    style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; margin-top: 1rem; width: 100%;">
                                <img src="assets/Ícones/lixo.png" alt="Avatar" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;"> <?php echo __('favorites.remove_button'); ?>
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

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>