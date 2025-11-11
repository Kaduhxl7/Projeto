<?php
require_once __DIR__ . '/../config/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('search_stores_title') ?> - DressCode</title>
    <link rel="stylesheet" href="/Projeto/Projeto-master/public/assets/css/style.css">
    <style>
        .search-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-input-group {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .search-btn, .location-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .search-btn {
            background: #5e2b2b;
            color: white;
        }
        
        .location-btn {
            background: #28a745;
            color: white;
        }
        
        .search-btn:hover, .location-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .filters-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .results-count {
            color: #666;
            font-size: 14px;
        }
        
        .brecho-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .brecho-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .brecho-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .brecho-name {
            font-size: 18px;
            font-weight: 600;
            color: #5e2b2b;
            margin-bottom: 8px;
        }
        
        .brecho-address {
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .brecho-distance {
            color: #28a745;
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .brecho-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-details {
            background: #5e2b2b;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .btn-details:hover {
            background: #4a2222;
        }
        
        .btn-route {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #5e2b2b;
        }
        
        .pagination .current {
            background: #5e2b2b;
            color: white;
        }
        
        @media (max-width: 768px) {
            .search-input-group {
                flex-direction: column;
            }
            
            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .brecho-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <main class="search-container">
        <div class="search-form">
            <h1><?= __('search_stores_title') ?></h1>
            <p><?= __('search_stores_description') ?></p>
            
            <form id="search-form" method="GET">
                <div class="search-input-group">
                    <input type="text" 
                           id="campo-busca" 
                           name="search"
                           class="search-input" 
                           placeholder="<?= __('search_placeholder') ?>"
                           value="<?= htmlspecialchars($search_term) ?>">
                    <button type="submit" class="search-btn"><?= __('search_button') ?></button>
                    <button type="button" id="btn-localizacao" class="location-btn">
                        📍 <?= __('use_my_location') ?>
                    </button>
                </div>
                
                <div class="filters-row">
                    <select name="estado" class="filter-select">
                        <option value=""><?= __('all_states') ?></option>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= $estado ?>" <?= ($filters['estado'] ?? '') === $estado ? 'selected' : '' ?>>
                                <?= $estado ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select name="max_distance" class="filter-select">
                        <option value=""><?= __('any_distance') ?></option>
                        <option value="10" <?= ($filters['max_distance'] ?? '') == '10' ? 'selected' : '' ?>>
                            <?= __('up_to_km') ?>
                        </option>
                        <option value="25" <?= ($filters['max_distance'] ?? '') == '25' ? 'selected' : '' ?>>
                            Até 25 km
                        </option>
                        <option value="50" <?= ($filters['max_distance'] ?? '') == '50' ? 'selected' : '' ?>>
                            Até 50 km
                        </option>
                    </select>
                    
                    <select name="order" class="filter-select">
                        <option value=""><?= __('sort_by_relevance') ?></option>
                        <option value="nome" <?= ($filters['order'] ?? '') === 'nome' ? 'selected' : '' ?>>
                            <?= __('sort_by_name') ?>
                        </option>
                        <option value="cidade" <?= ($filters['order'] ?? '') === 'cidade' ? 'selected' : '' ?>>
                            <?= __('sort_by_city') ?>
                        </option>
                    </select>
                </div>
                
                <input type="hidden" id="latitude" name="latitude" value="<?= $filters['latitude'] ?? '' ?>">
                <input type="hidden" id="longitude" name="longitude" value="<?= $filters['longitude'] ?? '' ?>">
            </form>
        </div>
        
        <div class="results-header">
            <div class="results-count">
                <?php if ($total_results > 0): ?>
                    <?= str_replace('{count}', $total_results, __('results_found')) ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="resultados">
            <?php if (empty($brechos)): ?>
                <div class="no-results">
                    <h3><?= __('no_results_found') ?></h3>
                    <p><?= __('try_different_search') ?></p>
                </div>
            <?php else: ?>
                <div class="brecho-grid">
                    <?php foreach ($brechos as $brecho): ?>
                        <div class="brecho-card">
                            <h3 class="brecho-name"><?= htmlspecialchars($brecho['nome']) ?></h3>
                            <p class="brecho-address">
                                <?= htmlspecialchars($brecho['endereco']) ?> - <?= htmlspecialchars($brecho['cidade']) ?>, <?= htmlspecialchars($brecho['estado']) ?>
                            </p>
                            
                            <?php if (isset($brecho['distancia'])): ?>
                                <p class="brecho-distance">
                                    <strong><?= __('distance') ?>:</strong> <?= round($brecho['distancia'], 2) ?> km
                                </p>
                            <?php endif; ?>
                            
                            <?php if (!empty($brecho['descricao'])): ?>
                                <p class="brecho-description"><?= htmlspecialchars($brecho['descricao']) ?></p>
                            <?php endif; ?>
                            
                            <div class="brecho-actions">
                                <a href="/Projeto/Projeto-master/public/brecho.php?id=<?= $brecho['id'] ?>" class="btn-details">
                                    <?= __('view_details') ?>
                                </a>
                                <?php if (isset($brecho['latitude']) && isset($brecho['longitude'])): ?>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $brecho['latitude'] ?>,<?= $brecho['longitude'] ?>" 
                                       target="_blank" class="btn-route">
                                        <?= __('get_directions') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1])) ?>">
                                <?= __('pagination.previous') ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1])) ?>">
                                <?= __('pagination.next') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
    
    <script>
        // Geolocalização
        document.getElementById('btn-localizacao').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('<?= __('geolocation_not_supported') ?>');
                return;
            }
            
            this.textContent = '📍 <?= __('getting_location') ?>...';
            this.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Submeter formulário automaticamente
                    document.getElementById('search-form').submit();
                },
                function(error) {
                    alert('<?= __('location_error') ?>: ' + error.message);
                    document.getElementById('btn-localizacao').textContent = '📍 <?= __('use_my_location') ?>';
                    document.getElementById('btn-localizacao').disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                }
            );
        });
        
        // Busca em tempo real (opcional)
        let searchTimeout;
        document.getElementById('campo-busca').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Implementar busca AJAX se necessário
            }, 500);
        });
    </script>
</body>
</html>