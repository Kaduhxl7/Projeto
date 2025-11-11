<?php
$page_title = __('search.title') . (!empty($data['search_term']) ? " " . __('search.results_for') . " '" . htmlspecialchars($data['search_term']) . "'" : "") . " - " . __('site.title');
$page_description = __('site.description');
$additional_css = ['assets/css/pages.css'];
ob_start();
?>

<div class="search-page">
    <div class="container">
        <div class="page-header">
            <h1><?php echo __('search.title'); ?></h1>
            <?php if (!empty($data['search_term'])): ?>
                <p><?php echo __('search.searching_for'); ?>: <strong>"<?php echo htmlspecialchars($data['search_term']); ?>"</strong></p>
            <?php endif; ?>
            <div class="results-info">
                <?php echo $data['pagination']['total_items']; ?> <?php echo __('search.results_found'); ?>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Sidebar com filtros -->
            <aside class="filters-sidebar">
                <h3><?php echo __('search.refine_search'); ?></h3>
                
                <form method="GET" action="" id="filtersForm">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['search_term']); ?>">
                    
                    <!-- Filtro por preço -->
                    <div class="filter-group">
                        <details open>
                            <summary><?php echo __('filters.price'); ?></summary>
                            <div class="price-range">
                                <input type="number" name="preco_min" placeholder="<?php echo __('filters.min_price'); ?>" 
                                       value="<?php echo $data['filtros_ativos']['preco_min'] ?? ''; ?>">
                                <input type="number" name="preco_max" placeholder="<?php echo __('filters.max_price'); ?>" 
                                       value="<?php echo $data['filtros_ativos']['preco_max'] ?? ''; ?>">
                            </div>
                        </details>
                    </div>

                    <!-- Filtro por cor -->
                    <?php if (!empty($data['filtros_disponiveis']['cores'])): ?>
                    <div class="filter-group">
                        <details open>
                            <summary><?php echo __('filters.color'); ?></summary>
                            <ul>
                                <?php foreach ($data['filtros_disponiveis']['cores'] as $cor): ?>
                                <li>
                                    <input type="checkbox" name="cor[]" value="<?php echo htmlspecialchars($cor); ?>" 
                                           id="cor_<?php echo htmlspecialchars($cor); ?>"
                                           <?php echo in_array($cor, (array)($data['filtros_ativos']['cor'] ?? [])) ? 'checked' : ''; ?>>
                                    <label for="cor_<?php echo htmlspecialchars($cor); ?>"><?php echo TranslationHelper::translateColor($cor); ?></label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    </div>
                    <?php endif; ?>

                    <!-- Filtro por tamanho -->
                    <?php if (!empty($data['filtros_disponiveis']['tamanhos'])): ?>
                    <div class="filter-group">
                        <details open>
                            <summary><?php echo __('filters.size'); ?></summary>
                            <ul>
                                <?php foreach ($data['filtros_disponiveis']['tamanhos'] as $tamanho): ?>
                                <li>
                                    <input type="checkbox" name="tamanho[]" value="<?php echo htmlspecialchars($tamanho); ?>" 
                                           id="tam_<?php echo htmlspecialchars($tamanho); ?>"
                                           <?php echo in_array($tamanho, (array)($data['filtros_ativos']['tamanho'] ?? [])) ? 'checked' : ''; ?>>
                                    <label for="tam_<?php echo htmlspecialchars($tamanho); ?>"><?php echo TranslationHelper::translateSize($tamanho); ?></label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    </div>
                    <?php endif; ?>

                    <!-- Filtro por marca -->
                    <?php if (!empty($data['filtros_disponiveis']['marcas'])): ?>
                    <div class="filter-group">
                        <details>
                            <summary><?php echo __('filters.brand'); ?></summary>
                            <ul>
                                <?php foreach ($data['filtros_disponiveis']['marcas'] as $marca): ?>
                                <li>
                                    <input type="checkbox" name="marca[]" value="<?php echo htmlspecialchars($marca); ?>" 
                                           id="marca_<?php echo htmlspecialchars($marca); ?>"
                                           <?php echo in_array($marca, (array)($data['filtros_ativos']['marca'] ?? [])) ? 'checked' : ''; ?>>
                                    <label for="marca_<?php echo htmlspecialchars($marca); ?>"><?php echo htmlspecialchars($marca); ?></label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    </div>
                    <?php endif; ?>

                    <div class="filter-actions">
                        <button type="submit" class="btn-apply"><?php echo __('search.apply_filters'); ?></button>
                        <a href="?search=<?php echo urlencode($data['search_term']); ?>" class="btn-clear"><?php echo __('search.clear_filters'); ?></a>
                    </div>
                </form>
            </aside>

            <!-- Lista de produtos -->
            <main class="products-main">
                <!-- Ordenação -->
                <div class="sort-bar">
                    <select name="order" onchange="updateSort(this)">
                        <option value="created_at-DESC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'created_at' ? 'selected' : ''; ?>><?php echo __('sort.newest'); ?></option>
                        <option value="preco-ASC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'preco' && ($data['filtros_ativos']['dir'] ?? '') == 'ASC' ? 'selected' : ''; ?>><?php echo __('sort.price_low'); ?></option>
                        <option value="preco-DESC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'preco' && ($data['filtros_ativos']['dir'] ?? '') == 'DESC' ? 'selected' : ''; ?>><?php echo __('sort.price_high'); ?></option>
                        <option value="nome-ASC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'nome' ? 'selected' : ''; ?>><?php echo __('sort.name_az'); ?></option>
                    </select>
                </div>

                <!-- Grid de produtos -->
                <div class="products-grid">
                    <?php if (empty($data['produtos'])): ?>
                        <div class="no-products">
                            <h3><?php echo __('search.no_results'); ?></h3>
                            <p><?php echo __('search.try_different_terms'); ?></p>
                            <div class="suggestions">
                                <h4><?php echo __('search.suggestions'); ?>:</h4>
                                <a href="categoria.php?cat=feminino" class="suggestion-link"><?php echo __('categories.feminino'); ?></a>
                                <a href="categoria.php?cat=masculino" class="suggestion-link"><?php echo __('categories.masculino'); ?></a>
                                <a href="categoria.php?cat=acessorios" class="suggestion-link"><?php echo __('categories.acessorios'); ?></a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['produtos'] as $produto): ?>
                            <div class="produto-card" onclick="window.location.href='<?php echo url_with_lang('produto.php?id=' . $produto['id']); ?>'">
                                <div class="produto-image-container">
                                    <img src="assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                         alt="<?php echo htmlspecialchars($produto['nome']); ?>" loading="lazy">
                                    <div class="produto-overlay">
                                        <div class="overlay-content">
                                            <span class="view-icon">👁️</span>
                                            <span class="view-text"><?php echo __('product.view_details'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="produto-info">
                                    <h3><?php echo TranslationHelper::translateDynamic($produto['nome'], 'product_name'); ?></h3>
                                    <div class="produto-preco"><?php echo TranslationHelper::formatPrice($produto['preco']); ?></div>
                                    <div class="produto-detalhes">
                                        <span class="categoria"><?php echo htmlspecialchars($produto['categoria_nome']); ?></span>
                                        <span class="tamanho"><?php echo TranslationHelper::translateSize($produto['tamanho']); ?></span>
                                        <span class="cor"><?php echo TranslationHelper::translateColor($produto['cor']); ?></span>
                                    </div>
                                    <span class="produto-condicao <?php echo strtolower($produto['condicao']); ?>">
                                        <?php echo __('conditions.' . strtolower($produto['condicao'])); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Paginação -->
                <?php if ($data['pagination']['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php
                    $current_page = $data['pagination']['current_page'];
                    $total_pages = $data['pagination']['total_pages'];
                    $query_params = $_GET;
                    ?>
                    
                    <?php if ($current_page > 1): ?>
                        <?php $query_params['page'] = $current_page - 1; ?>
                        <a href="?<?php echo http_build_query($query_params); ?>" class="page-link">« <?php echo __('pagination.previous'); ?></a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                        <?php $query_params['page'] = $i; ?>
                        <a href="?<?php echo http_build_query($query_params); ?>" 
                           class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <?php $query_params['page'] = $current_page + 1; ?>
                        <a href="?<?php echo http_build_query($query_params); ?>" class="page-link"><?php echo __('pagination.next'); ?> »</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<style>
.search-page { padding: 2rem 0; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.page-header { text-align: center; margin-bottom: 2rem; }
.page-header h1 { color: #5e2b2b; font-size: 2.5rem; margin-bottom: 0.5rem; }
.results-info { color: #666; margin-top: 1rem; }
.content-wrapper { display: flex; gap: 2rem; }
.filters-sidebar { width: 250px; background: white; padding: 1.5rem; border-radius: 12px; height: fit-content; }
.filter-group { margin-bottom: 1.5rem; }
.filter-group summary { font-weight: 600; cursor: pointer; margin-bottom: 1rem; }
.filter-group ul { list-style: none; padding: 0; }
.filter-group li { margin-bottom: 0.5rem; }
.price-range { display: flex; gap: 0.5rem; }
.price-range input { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
.filter-actions { margin-top: 1rem; }
.btn-apply, .btn-clear { display: block; width: 100%; padding: 0.75rem; margin-bottom: 0.5rem; text-align: center; text-decoration: none; border-radius: 6px; }
.btn-apply { background: #5e2b2b; color: white; border: none; cursor: pointer; }
.btn-clear { background: #f5f5f5; color: #666; }
.products-main { flex: 1; }
.sort-bar { margin-bottom: 1.5rem; text-align: right; }
.sort-bar select { padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }

/* Efeitos hover para produtos */
.produto-card {
    position: relative;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
}

.produto-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(94, 43, 43, 0.2);
}

.produto-image-container {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.produto-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.produto-card:hover .produto-image-container img {
    transform: scale(1.1) rotate(2deg);
    filter: brightness(0.8);
}

.produto-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(94, 43, 43, 0.8), rgba(0, 0, 0, 0.6));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.produto-card:hover .produto-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
    transform: translateY(20px);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.produto-card:hover .overlay-content {
    transform: translateY(0);
}

.view-icon {
    display: block;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    animation: pulse 2s infinite;
}

.view-text {
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.produto-info {
    padding: 1.5rem;
    position: relative;
    z-index: 2;
}

.produto-info h3 {
    color: #5e2b2b;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.produto-card:hover .produto-info h3 {
    color: #4a2323;
}

.produto-preco {
    font-size: 1.3rem;
    font-weight: bold;
    color: #5e2b2b;
    margin-bottom: 0.75rem;
}

.produto-detalhes {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    flex-wrap: wrap;
}

.produto-detalhes span {
    background: #f5f5f5;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    color: #666;
    transition: all 0.3s ease;
}

.produto-card:hover .produto-detalhes span {
    background: #5e2b2b;
    color: white;
    transform: translateY(-2px);
}

.produto-condicao {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.produto-condicao.novo {
    background: #d4edda;
    color: #155724;
}

.produto-condicao.seminovo {
    background: #fff3cd;
    color: #856404;
}

.produto-condicao.usado {
    background: #f8d7da;
    color: #721c24;
}

/* Efeito de brilho */
.produto-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.6s;
    opacity: 0;
    z-index: 1;
}

.produto-card:hover::before {
    animation: shine 0.8s ease-in-out;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
        opacity: 0;
    }
}
.no-products { text-align: center; padding: 3rem; color: #666; }
.suggestions { margin-top: 2rem; }
.suggestion-link { display: inline-block; margin: 0.5rem; padding: 0.5rem 1rem; background: #5e2b2b; color: white; text-decoration: none; border-radius: 6px; }
.pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; }
.page-link { padding: 0.5rem 1rem; border: 1px solid #ddd; text-decoration: none; color: #5e2b2b; border-radius: 4px; }
.page-link.active { background: #5e2b2b; color: white; }
@media (max-width: 768px) {
    .content-wrapper { flex-direction: column; }
    .filters-sidebar { width: 100%; }
    .products-grid { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); }
}
</style>

<script>
function updateSort(select) {
    const [order, dir] = select.value.split('-');
    const url = new URL(window.location);
    url.searchParams.set('order', order);
    url.searchParams.set('dir', dir);
    window.location.href = url.toString();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>