<?php
$page_title = "Busca" . (!empty($data['search_term']) ? " por '" . htmlspecialchars($data['search_term']) . "'" : "") . " - DressCode";
$page_description = "Resultados da busca por produtos de moda sustentável no DressCode.";
$additional_css = ['assets/css/pages.css'];
ob_start();
?>

<div class="search-page">
    <div class="container">
        <div class="page-header">
            <h1>Resultados da Busca</h1>
            <?php if (!empty($data['search_term'])): ?>
                <p>Buscando por: <strong>"<?php echo htmlspecialchars($data['search_term']); ?>"</strong></p>
            <?php endif; ?>
            <div class="results-info">
                <?php echo $data['pagination']['total_items']; ?> produtos encontrados
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Sidebar com filtros -->
            <aside class="filters-sidebar">
                <h3>Refinar Busca</h3>
                
                <form method="GET" action="" id="filtersForm">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($data['search_term']); ?>">
                    
                    <!-- Filtro por preço -->
                    <div class="filter-group">
                        <details open>
                            <summary>Preço</summary>
                            <div class="price-range">
                                <input type="number" name="preco_min" placeholder="Min" 
                                       value="<?php echo $data['filtros_ativos']['preco_min'] ?? ''; ?>">
                                <input type="number" name="preco_max" placeholder="Max" 
                                       value="<?php echo $data['filtros_ativos']['preco_max'] ?? ''; ?>">
                            </div>
                        </details>
                    </div>

                    <!-- Filtro por cor -->
                    <?php if (!empty($data['filtros_disponiveis']['cores'])): ?>
                    <div class="filter-group">
                        <details open>
                            <summary>Cor</summary>
                            <ul>
                                <?php foreach ($data['filtros_disponiveis']['cores'] as $cor): ?>
                                <li>
                                    <input type="checkbox" name="cor[]" value="<?php echo htmlspecialchars($cor); ?>" 
                                           id="cor_<?php echo htmlspecialchars($cor); ?>"
                                           <?php echo in_array($cor, (array)($data['filtros_ativos']['cor'] ?? [])) ? 'checked' : ''; ?>>
                                    <label for="cor_<?php echo htmlspecialchars($cor); ?>"><?php echo htmlspecialchars($cor); ?></label>
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
                            <summary>Tamanho</summary>
                            <ul>
                                <?php foreach ($data['filtros_disponiveis']['tamanhos'] as $tamanho): ?>
                                <li>
                                    <input type="checkbox" name="tamanho[]" value="<?php echo htmlspecialchars($tamanho); ?>" 
                                           id="tam_<?php echo htmlspecialchars($tamanho); ?>"
                                           <?php echo in_array($tamanho, (array)($data['filtros_ativos']['tamanho'] ?? [])) ? 'checked' : ''; ?>>
                                    <label for="tam_<?php echo htmlspecialchars($tamanho); ?>"><?php echo htmlspecialchars($tamanho); ?></label>
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
                            <summary>Marca</summary>
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
                        <button type="submit" class="btn-apply">Aplicar Filtros</button>
                        <a href="?search=<?php echo urlencode($data['search_term']); ?>" class="btn-clear">Limpar Filtros</a>
                    </div>
                </form>
            </aside>

            <!-- Lista de produtos -->
            <main class="products-main">
                <!-- Ordenação -->
                <div class="sort-bar">
                    <select name="order" onchange="updateSort(this)">
                        <option value="created_at-DESC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'created_at' ? 'selected' : ''; ?>>Mais recentes</option>
                        <option value="preco-ASC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'preco' && ($data['filtros_ativos']['dir'] ?? '') == 'ASC' ? 'selected' : ''; ?>>Menor preço</option>
                        <option value="preco-DESC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'preco' && ($data['filtros_ativos']['dir'] ?? '') == 'DESC' ? 'selected' : ''; ?>>Maior preço</option>
                        <option value="nome-ASC" <?php echo ($data['filtros_ativos']['order'] ?? '') == 'nome' ? 'selected' : ''; ?>>Nome A-Z</option>
                    </select>
                </div>

                <!-- Grid de produtos -->
                <div class="products-grid">
                    <?php if (empty($data['produtos'])): ?>
                        <div class="no-products">
                            <h3>Nenhum produto encontrado</h3>
                            <p>Tente usar termos diferentes ou explore nossas categorias.</p>
                            <div class="suggestions">
                                <h4>Sugestões:</h4>
                                <a href="categoria.php?cat=feminino" class="suggestion-link">Moda Feminina</a>
                                <a href="categoria.php?cat=masculino" class="suggestion-link">Moda Masculina</a>
                                <a href="categoria.php?cat=acessorios" class="suggestion-link">Acessórios</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['produtos'] as $produto): ?>
                            <div class="produto-card" onclick="window.location.href='produto.php?id=<?php echo $produto['id']; ?>'">
                                <img src="assets/images/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($produto['nome']); ?>" loading="lazy">
                                
                                <div class="produto-info">
                                    <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                                    <div class="produto-preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                                    <div class="produto-detalhes">
                                        <span class="categoria"><?php echo htmlspecialchars($produto['categoria_nome']); ?></span>
                                        <span class="tamanho"><?php echo htmlspecialchars($produto['tamanho']); ?></span>
                                        <span class="cor"><?php echo htmlspecialchars($produto['cor']); ?></span>
                                    </div>
                                    <span class="produto-condicao <?php echo strtolower($produto['condicao']); ?>">
                                        <?php echo htmlspecialchars($produto['condicao']); ?>
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
                        <a href="?<?php echo http_build_query($query_params); ?>" class="page-link">« Anterior</a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                        <?php $query_params['page'] = $i; ?>
                        <a href="?<?php echo http_build_query($query_params); ?>" 
                           class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($current_page < $total_pages): ?>
                        <?php $query_params['page'] = $current_page + 1; ?>
                        <a href="?<?php echo http_build_query($query_params); ?>" class="page-link">Próxima »</a>
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