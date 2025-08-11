<?php
$page_title = ucfirst($data['categoria']['nome']) . " - DressCode";
$page_description = "Descubra peças únicas de " . strtolower($data['categoria']['nome']) . " em brechós online. Moda sustentável com estilo.";
$additional_css = ['assets/css/pages.css'];
ob_start();
?>

<div class="category-page">
    <div class="container">
        <div class="page-header">
            <h1><?php echo htmlspecialchars($data['categoria']['nome']); ?></h1>
            <p><?php echo htmlspecialchars($data['categoria']['descricao']); ?></p>
            <div class="results-info">
                <?php echo $data['pagination']['total_items']; ?> produtos encontrados
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Sidebar com filtros -->
            <aside class="filters-sidebar">
                <h3>Filtros</h3>
                
                <form method="GET" action="" id="filtersForm">
                    <input type="hidden" name="cat" value="<?php echo htmlspecialchars($data['categoria']['slug']); ?>">
                    
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
                        <a href="?cat=<?php echo htmlspecialchars($data['categoria']['slug']); ?>" class="btn-clear">Limpar</a>
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
                            <p>Tente ajustar os filtros ou explore outras categorias.</p>
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
                                        <span class="tamanho"><?php echo htmlspecialchars($produto['tamanho']); ?></span>
                                        <span class="cor"><?php echo htmlspecialchars($produto['cor']); ?></span>
                                        <span class="marca"><?php echo htmlspecialchars($produto['marca']); ?></span>
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