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
            <a href="index.php"><?php echo __('nav.home'); ?></a> > 
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
                <h1><?php echo TranslationHelper::translateDynamic($data['produto']['nome'], 'product_name'); ?></h1>
                
                <div class="price">
                    <span class="current-price"><?php echo TranslationHelper::formatPrice($data['produto']['preco']); ?></span>
                </div>

                <div class="product-details">
                    <div class="detail-item">
                        <strong><?php echo __('product.size'); ?>:</strong> <?php echo TranslationHelper::translateSize($data['produto']['tamanho']); ?>
                    </div>
                    <div class="detail-item">
                        <strong><?php echo __('product.color'); ?>:</strong> <?php echo TranslationHelper::translateColor($data['produto']['cor']); ?>
                    </div>
                    <div class="detail-item">
                        <strong><?php echo __('product.brand'); ?>:</strong> <?php echo htmlspecialchars($data['produto']['marca']); ?>
                    </div>
                    <div class="detail-item">
                        <strong><?php echo __('product.condition'); ?>:</strong> 
                        <span class="condition <?php echo strtolower($data['produto']['condicao']); ?>">
                            <?php echo __('conditions.' . strtolower($data['produto']['condicao'])); ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <strong><?php echo __('filters.category'); ?>:</strong> <?php echo htmlspecialchars($data['produto']['categoria_nome']); ?>
                    </div>
                </div>

                <?php if (!empty($data['produto']['descricao'])): ?>
                <div class="description">
                    <h3><?php echo __('product.description'); ?></h3>
                    <p><?php echo nl2br(TranslationHelper::translateDynamic($data['produto']['descricao'], 'product_description')); ?></p>
                </div>
                <?php endif; ?>

                <div class="actions">
                    <button class="btn-primary" onclick="mostrarInteresse()">
                        <img src="assets/Ícones/balao-de-fala.png" alt="Mensagem" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px;"><?php echo __('product.contact_seller'); ?>
                    </button>
                    <button class="btn-secondary" onclick="adicionarFavoritos(<?php echo $data['produto']['id']; ?>)">
                        <img src="assets/Ícones/coracao.png" alt="Coracao" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px;"><?php echo __('product.add_to_favorites'); ?>
                    </button>
                    <button class="btn-secondary" onclick="abrirModalAvaliacao(<?php echo $data['produto']['id']; ?>)">
                        <img src="assets/Ícones/estrela.png" alt="Estrela" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px;"> Avaliar Produto
                    </button>
                    <button class="btn-secondary" onclick="abrirModalReporte(<?php echo $data['produto']['id']; ?>)">
                        <img src="assets/Ícones/nao-gosto.png" alt="Deslike" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px;"><?php echo __('report.button'); ?>
                    </button>
                    <div class="share-container">
                        <button class="btn-secondary" onclick="toggleShareMenu()">
                            <img src="assets/Ícones/compartilhar.png" alt="Avatar" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;"> <?php echo __('product.share'); ?>
                        </button>
                        <div id="shareMenu" class="share-menu">
                            <div class="share-header"><?php echo __('share.title'); ?></div>
                            <button onclick="shareWhatsApp()" class="share-option">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <?php echo __('share.whatsapp'); ?>
                            </button>
                            <button onclick="shareFacebook()" class="share-option">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <?php echo __('share.facebook'); ?>
                            </button>
                            <button onclick="shareTwitter()" class="share-option">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <?php echo __('share.twitter'); ?>
                            </button>
                            <button onclick="shareInstagram()" class="share-option">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                <?php echo __('share.instagram'); ?>
                            </button>
                            <button onclick="copyLink()" class="share-option">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
                                </svg>
                                <?php echo __('share.copy_link'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-stats">
                    <small><img src="assets/Ícones/olho.png" alt="Olho" style="width: 25px; height: 25px; vertical-align: middle; margin-right: 5px;"> <?php echo $data['produto']['visualizacoes']; ?> <?php echo __('product.view_count'); ?></small>
                    <small><img src="assets/Ícones/calendario.gif" alt="Calendário" style="width: 25px; height: 25px; vertical-align: middle; margin-right: 5px;"> <?php echo __('product.published_on'); ?> <?php echo date('d/m/Y', strtotime($data['produto']['created_at'])); ?></small>
                </div>
            </div>
        </div>

        <!-- Produtos relacionados -->
        <?php if (!empty($data['produtos_relacionados'])): ?>
        <section class="related-products">
            <h2><?php echo __('product.related_products'); ?></h2>
            <div class="products-grid">
                <?php foreach ($data['produtos_relacionados'] as $produto): ?>
                    <div class="produto-card" onclick="window.location.href='produto.php?id=<?php echo $produto['id']; ?>'">
                        <img src="assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" loading="lazy">
                        
                        <div class="produto-info">
                            <h3><?php echo TranslationHelper::translateDynamic($produto['nome'], 'product_name'); ?></h3>
                            <div class="produto-preco"><?php echo TranslationHelper::formatPrice($produto['preco']); ?></div>
                            <div class="produto-detalhes">
                                <span><?php echo TranslationHelper::translateSize($produto['tamanho']); ?></span>
                                <span><?php echo TranslationHelper::translateColor($produto['cor']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Avaliações -->
        <section class="avaliacoes-section">
            <h2><img src="assets/Ícones/estrela.png" alt="Estrela" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 8px;"> <?php echo __('product.reviews'); ?></h2>
            <div id="avaliacoes-container">
                <!-- Avaliações serão carregadas via JavaScript -->
            </div>
        </section>
    </div>
</div>

<!-- Modal de Avaliação -->
<div id="modalAvaliacao" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem; color: #5e2b2b;">⭐ Avaliar Produto</h3>
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nota:</label>
            <div class="rating-stars">
                <span class="star" data-rating="1">★</span>
                <span class="star" data-rating="2">★</span>
                <span class="star" data-rating="3">★</span>
                <span class="star" data-rating="4">★</span>
                <span class="star" data-rating="5">★</span>
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Comentário:</label>
            <textarea id="comentarioAvaliacao" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e1d8d8; border-radius: 8px; resize: vertical;" placeholder="Conte sua experiência com este produto..."></textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button onclick="enviarAvaliacao()" style="flex: 1; background: #5e2b2b; color: white; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Enviar Avaliação
            </button>
            <button onclick="fecharModalAvaliacao()" style="flex: 1; background: #f5f5f5; color: #5e2b2b; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Modal de Relatório -->
<div id="modalReporte" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1rem; color: #5e2b2b;">⚠️ <?php echo __('report.title'); ?></h3>
        
        <div style="margin-bottom: 1rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;"><?php echo __('report.reason'); ?>:</label>
            <select id="motivoReporte" style="width: 100%; padding: 0.75rem; border: 2px solid #e1d8d8; border-radius: 8px;">
                <option value=""><?php echo __('report.reason_required'); ?></option>
                <option value="incorrect_info"><?php echo __('report.reasons.incorrect_info'); ?></option>
                <option value="wrong_image"><?php echo __('report.reasons.wrong_image'); ?></option>
                <option value="out_of_stock"><?php echo __('report.reasons.out_of_stock'); ?></option>
                <option value="suspicious"><?php echo __('report.reasons.suspicious'); ?></option>
                <option value="other"><?php echo __('report.reasons.other'); ?></option>
            </select>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;"><?php echo __('report.description'); ?>:</label>
            <textarea id="descricaoReporte" rows="4" style="width: 100%; padding: 0.75rem; border: 2px solid #e1d8d8; border-radius: 8px; resize: vertical;" placeholder="<?php echo __('report.description_placeholder'); ?>"></textarea>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button onclick="enviarReporte()" style="flex: 1; background: #5e2b2b; color: white; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <?php echo __('report.submit'); ?>
            </button>
            <button onclick="fecharModalReporte()" style="flex: 1; background: #f5f5f5; color: #5e2b2b; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <?php echo __('report.cancel'); ?>
            </button>
        </div>
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
.avaliacoes-section { margin-top: 3rem; }
.avaliacoes-section h2 { color: #5e2b2b; margin-bottom: 2rem; }
.avaliacao-item { background: #f9f9f9; padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; }
.avaliacao-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.avaliacao-usuario { font-weight: 600; color: #5e2b2b; }
.avaliacao-data { color: #666; font-size: 0.9rem; }
.avaliacao-nota { color: #ffc107; margin-bottom: 0.5rem; }
.avaliacao-comentario { line-height: 1.6; }
.rating-stars { display: flex; gap: 0.25rem; margin-bottom: 0.5rem; }
.star { font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
.star:hover, .star.active { color: #ffc107; }
.estatisticas-avaliacoes { background: #f5f5f5; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; }
.media-nota { font-size: 2rem; font-weight: bold; color: #5e2b2b; text-align: center; }
.total-avaliacoes { text-align: center; color: #666; margin-bottom: 1rem; }
.share-container { position: relative; }
.share-menu { position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; display: none; margin-top: 0.5rem; }
.share-menu.show { display: block; }
.share-header { padding: 1rem; font-weight: 600; color: #5e2b2b; border-bottom: 1px solid #eee; text-align: center; }
.share-option { width: 100%; padding: 0.75rem 1rem; border: none; background: none; text-align: left; cursor: pointer; transition: background-color 0.2s; display: flex; align-items: center; gap: 0.75rem; color: #333; }
.share-option:hover { background-color: #f5f5f5; }
.share-option svg { flex-shrink: 0; }
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
    <?php if (isset($_SESSION['user_id'])): ?>
        fetch('toggle-favorito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                produto_id: produtoId,
                action: 'toggle'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na resposta: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Resposta:', data);
            Swal.fire({
                title: data.status === 'success' ? '❤️ Sucesso!' : '❌ Erro',
                text: data.message,
                icon: data.status,
                confirmButtonColor: '#5e2b2b'
            });
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                title: '❌ Erro',
                text: 'Erro ao processar solicitação: ' + error.message,
                icon: 'error',
                confirmButtonColor: '#5e2b2b'
            });
        });
    <?php else: ?>
        Swal.fire({
            title: '🔐 Login Necessário',
            text: 'Você precisa estar logado para adicionar favoritos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Fazer Login',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5e2b2b'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            }
        });
    <?php endif; ?>
}

function toggleShareMenu() {
    const menu = document.getElementById('shareMenu');
    menu.classList.toggle('show');
    
    // Fechar menu ao clicar fora
    if (menu.classList.contains('show')) {
        document.addEventListener('click', closeShareMenuOutside);
    } else {
        document.removeEventListener('click', closeShareMenuOutside);
    }
}

function closeShareMenuOutside(event) {
    const menu = document.getElementById('shareMenu');
    const container = document.querySelector('.share-container');
    
    if (!container.contains(event.target)) {
        menu.classList.remove('show');
        document.removeEventListener('click', closeShareMenuOutside);
    }
}

function shareWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Confira este produto no DressCode: <?php echo htmlspecialchars($data['produto']['nome']); ?>');
    window.open(`https://wa.me/?text=${text} ${url}`, '_blank');
    toggleShareMenu();
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    toggleShareMenu();
}

function shareTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('<?php echo htmlspecialchars($data['produto']['nome']); ?> - DressCode');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    toggleShareMenu();
}

function shareInstagram() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({
            title: '<?php echo __('share.instagram_copied'); ?>',
            text: '<?php echo __('share.instagram_copied_message'); ?>',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
        toggleShareMenu();
    }).catch(() => {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = window.location.href;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        Swal.fire({
            title: '<?php echo __('share.instagram_copied'); ?>',
            text: '<?php echo __('share.instagram_copied_message'); ?>',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
        });
        toggleShareMenu();
    });
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({
            title: '<?php echo __('share.link_copied'); ?>',
            text: '<?php echo __('share.link_copied_message'); ?>',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        toggleShareMenu();
    }).catch(() => {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = window.location.href;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        Swal.fire({
            title: '<?php echo __('share.link_copied'); ?>',
            text: '<?php echo __('share.link_copied_message'); ?>',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        toggleShareMenu();
    });
}

let notaSelecionada = 0;
let produtoIdAtual = 0;
let produtoIdReporte = 0;

function abrirModalAvaliacao(produtoId) {
    <?php if (isset($_SESSION['user_id'])): ?>
        produtoIdAtual = produtoId;
        document.getElementById('modalAvaliacao').style.display = 'flex';
        notaSelecionada = 0;
        document.getElementById('comentarioAvaliacao').value = '';
        atualizarEstrelas();
    <?php else: ?>
        Swal.fire({
            title: '🔐 Login Necessário',
            text: 'Você precisa estar logado para avaliar produtos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Fazer Login',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#5e2b2b'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            }
        });
    <?php endif; ?>
}

function fecharModalAvaliacao() {
    document.getElementById('modalAvaliacao').style.display = 'none';
}

function enviarAvaliacao() {
    if (notaSelecionada === 0) {
        Swal.fire('Erro', 'Por favor, selecione uma nota', 'error');
        return;
    }
    
    const comentario = document.getElementById('comentarioAvaliacao').value;
    
    fetch('adicionar-avaliacao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            produto_id: produtoIdAtual,
            nota: notaSelecionada,
            comentario: comentario
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Sucesso!', data.message, 'success').then(() => {
                fecharModalAvaliacao();
                carregarAvaliacoes(produtoIdAtual);
            });
        } else {
            Swal.fire('Erro', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Erro', 'Erro ao enviar avaliação', 'error');
    });
}

function atualizarEstrelas() {
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        star.classList.toggle('active', index < notaSelecionada);
        star.onclick = () => {
            notaSelecionada = index + 1;
            atualizarEstrelas();
        };
    });
}

function carregarAvaliacoes(produtoId) {
    fetch(`get-avaliacoes.php?produto_id=${produtoId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('avaliacoes-container');
            
            if (data.avaliacoes && data.avaliacoes.length > 0) {
                let html = '<div class="estatisticas-avaliacoes">';
                html += `<div class="media-nota">${parseFloat(data.estatisticas.media_nota).toFixed(1)} ★</div>`;
                html += `<div class="total-avaliacoes">${data.estatisticas.total_avaliacoes} avaliações</div>`;
                html += '</div>';
                
                data.avaliacoes.forEach(avaliacao => {
                    html += '<div class="avaliacao-item">';
                    html += '<div class="avaliacao-header">';
                    html += `<span class="avaliacao-usuario">${avaliacao.usuario_nome}</span>`;
                    html += `<span class="avaliacao-data">${new Date(avaliacao.created_at).toLocaleDateString('pt-BR')}</span>`;
                    html += '</div>';
                    html += `<div class="avaliacao-nota">${'★'.repeat(avaliacao.nota)}${'☆'.repeat(5-avaliacao.nota)}</div>`;
                    if (avaliacao.comentario) {
                        html += `<div class="avaliacao-comentario">${avaliacao.comentario}</div>`;
                    }
                    html += '</div>';
                });
                
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p style="text-align: center; color: #666;">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar avaliações:', error);
        });
}

function abrirModalReporte(produtoId) {
    produtoIdReporte = produtoId;
    document.getElementById('modalReporte').style.display = 'flex';
    document.getElementById('motivoReporte').value = '';
    document.getElementById('descricaoReporte').value = '';
}

function fecharModalReporte() {
    document.getElementById('modalReporte').style.display = 'none';
}

function enviarReporte() {
    const motivo = document.getElementById('motivoReporte').value;
    const descricao = document.getElementById('descricaoReporte').value;
    
    if (!motivo) {
        Swal.fire('Erro', '<?php echo __('report.reason_required'); ?>', 'error');
        return;
    }
    
    fetch('report-problem.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: produtoIdReporte,
            reason: motivo,
            description: descricao
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Sucesso!', data.message, 'success').then(() => {
                fecharModalReporte();
            });
        } else {
            Swal.fire('Erro', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Erro', '<?php echo __('report.error_message'); ?>', 'error');
    });
}

// Carregar avaliações ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const produtoId = <?php echo $data['produto']['id']; ?>;
    carregarAvaliacoes(produtoId);
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>