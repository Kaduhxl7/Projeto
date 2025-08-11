<?php
$page_title = htmlspecialchars($data['produto']['nome']) . " - DressCode";
$page_description = "Compre " . htmlspecialchars($data['produto']['nome']) . " por R$ " . number_format($data['produto']['preco'], 2, ',', '.') . ". Moda sustentável no DressCode.";
$additional_css = ['assets/css/pages.css'];
ob_start();
?>

<div class="product-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="index.php">Início</a> > 
            <a href="categoria.php?cat=<?php echo htmlspecialchars($data['produto']['categoria_slug']); ?>">
                <?php echo htmlspecialchars($data['produto']['categoria_nome']); ?>
            </a> > 
            <span><?php echo htmlspecialchars($data['produto']['nome']); ?></span>
        </nav>

        <div class="product-content">
            <!-- Imagem do produto -->
            <div class="product-image">
                <img src="assets/images/<?php echo htmlspecialchars($data['produto']['imagem']); ?>" 
                     alt="<?php echo htmlspecialchars($data['produto']['nome']); ?>">
            </div>

            <!-- Informações do produto -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($data['produto']['nome']); ?></h1>
                
                <div class="price">
                    <span class="current-price">R$ <?php echo number_format($data['produto']['preco'], 2, ',', '.'); ?></span>
                </div>

                <div class="product-details">
                    <div class="detail-item">
                        <strong>Tamanho:</strong> <?php echo htmlspecialchars($data['produto']['tamanho']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Cor:</strong> <?php echo htmlspecialchars($data['produto']['cor']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Marca:</strong> <?php echo htmlspecialchars($data['produto']['marca']); ?>
                    </div>
                    <div class="detail-item">
                        <strong>Condição:</strong> 
                        <span class="condition <?php echo strtolower($data['produto']['condicao']); ?>">
                            <?php echo htmlspecialchars($data['produto']['condicao']); ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong>Categoria:</strong> <?php echo htmlspecialchars($data['produto']['categoria_nome']); ?>
                    </div>
                </div>

                <?php if (!empty($data['produto']['descricao'])): ?>
                <div class="description">
                    <h3>Descrição</h3>
                    <p><?php echo nl2br(htmlspecialchars($data['produto']['descricao'])); ?></p>
                </div>
                <?php endif; ?>

                <div class="actions">
                    <button class="btn-primary" onclick="mostrarInteresse()">
                        💬 Demonstrar Interesse
                    </button>
                    <button class="btn-secondary" onclick="adicionarFavoritos(<?php echo $data['produto']['id']; ?>)">
                        ❤️ Adicionar aos Favoritos
                    </button>
                    <button class="btn-secondary" onclick="compartilhar()">
                        📤 Compartilhar
                    </button>
                </div>

                <div class="product-stats">
                    <small>👁️ <?php echo $data['produto']['visualizacoes']; ?> visualizações</small>
                    <small>📅 Publicado em <?php echo date('d/m/Y', strtotime($data['produto']['created_at'])); ?></small>
                </div>
            </div>
        </div>

        <!-- Produtos relacionados -->
        <?php if (!empty($data['produtos_relacionados'])): ?>
        <section class="related-products">
            <h2>Produtos Relacionados</h2>
            <div class="products-grid">
                <?php foreach ($data['produtos_relacionados'] as $produto): ?>
                    <div class="produto-card" onclick="window.location.href='produto.php?id=<?php echo $produto['id']; ?>'">
                        <img src="assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" loading="lazy">
                        
                        <div class="produto-info">
                            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                            <div class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                            <div class="produto-detalhes">
                                <span><?php echo htmlspecialchars($produto['tamanho']); ?></span>
                                <span><?php echo htmlspecialchars($produto['cor']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</div>

<style>
.product-detail { padding: 2rem 0; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.breadcrumb { margin-bottom: 2rem; color: #666; }
.breadcrumb a { color: #5e2b2b; text-decoration: none; }
.breadcrumb a:hover { text-decoration: underline; }
.product-content { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem; }
.product-image img { width: 100%; height: 500px; object-fit: cover; border-radius: 12px; }
.product-info h1 { color: #5e2b2b; font-size: 2rem; margin-bottom: 1rem; }
.price { margin-bottom: 2rem; }
.current-price { font-size: 2rem; font-weight: bold; color: #5e2b2b; }
.product-details { margin-bottom: 2rem; }
.detail-item { margin-bottom: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #eee; }
.condition.novo { color: #28a745; }
.condition.seminovo { color: #ffc107; }
.condition.usado { color: #dc3545; }
.description { margin-bottom: 2rem; }
.description h3 { color: #5e2b2b; margin-bottom: 1rem; }
.actions { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem; }
.btn-primary, .btn-secondary { padding: 1rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.btn-primary { background: #5e2b2b; color: white; }
.btn-primary:hover { background: #4a2323; }
.btn-secondary { background: #f5f5f5; color: #5e2b2b; }
.btn-secondary:hover { background: #e5e5e5; }
.product-stats { color: #666; font-size: 0.9rem; }
.product-stats small { display: block; margin-bottom: 0.25rem; }
.related-products { margin-top: 3rem; }
.related-products h2 { color: #5e2b2b; margin-bottom: 2rem; text-align: center; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; }
@media (max-width: 768px) {
    .product-content { grid-template-columns: 1fr; gap: 2rem; }
    .product-image img { height: 400px; }
    .actions { flex-direction: column; }
}
</style>

<script>
function mostrarInteresse() {
    Swal.fire({
        title: '💬 Demonstrar Interesse',
        text: 'Funcionalidade em desenvolvimento! Em breve você poderá entrar em contato diretamente com o vendedor.',
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#5e2b2b'
    });
}

function adicionarFavoritos(produtoId) {
    Swal.fire({
        title: '❤️ Favoritos',
        text: 'Funcionalidade em desenvolvimento! Em breve você poderá salvar seus produtos favoritos.',
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#5e2b2b'
    });
}

function compartilhar() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo htmlspecialchars($data['produto']['nome']); ?>',
            text: 'Confira este produto no DressCode!',
            url: window.location.href
        });
    } else {
        // Fallback para copiar URL
        navigator.clipboard.writeText(window.location.href).then(() => {
            Swal.fire({
                title: '📤 Link Copiado!',
                text: 'O link do produto foi copiado para a área de transferência.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>