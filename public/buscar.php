<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/config/database.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$search = $_GET['search'] ?? '';
$lat = $_GET['lat'] ?? '';
$lng = $_GET['lng'] ?? '';
$brechos = [];
$error = null;

if (!empty($search) || (!empty($lat) && !empty($lng))) {
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        if (!empty($lat) && !empty($lng)) {
            $stmt = $conn->prepare("
                SELECT *, 
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * 
                COS(RADIANS(longitude) - RADIANS(?)) + 
                SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distancia
                FROM brechos 
                WHERE latitude IS NOT NULL AND longitude IS NOT NULL
                HAVING distancia < 50
                ORDER BY distancia ASC
            ");
            $stmt->execute([$lat, $lng, $lat]);
        } else {
            $stmt = $conn->prepare("SELECT * FROM brechos WHERE cidade LIKE ? OR nome LIKE ? OR endereco LIKE ?");
            $searchParam = "%$search%";
            $stmt->execute([$searchParam, $searchParam, $searchParam]);
        }
        
        $brechos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="search-stores-page">
    <div class="search-stores-header">
        <h1><?= __('search_stores_title') ?></h1>
        <p><?= __('search_stores_description') ?></p>
    </div>

    <div class="search-stores-container">
        <div class="search-form">
            <form method="GET">
                <div class="form-group">
                    <input type="text" 
                           name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="<?= __('search_placeholder') ?>" 
                           class="search-input">
                    <button type="submit" class="btn-search">
                        🔍 <?= __('search_button') ?>
                    </button>
                    <button type="button" onclick="getLocation()" class="btn-location">
                        📍 <?= __('use_my_location') ?>
                    </button>
                </div>
            </form>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div id="results" class="results-container">
            <?php if (!empty($search) || (!empty($lat) && !empty($lng))): ?>
                <?php if (empty($brechos)): ?>
                    <div class="no-results">
                        <div class="no-results-icon">🔍</div>
                        <h3><?= __('no_results_found') ?></h3>
                        <p>
                        <?php if (!empty($search)): ?>
                            Nenhum brechó encontrado para "<?= htmlspecialchars($search) ?>"
                        <?php else: ?>
                            Nenhum brechó encontrado próximo à sua localização
                        <?php endif; ?>
                        </p>
                        <p><?= __('try_different_search') ?></p>
                    </div>
                <?php else: ?>
                    <div class="results-header">
                        <h3>
                            🎯 <?= count($brechos) ?> brechó(s) encontrado(s)
                            <?php if (!empty($search)): ?>
                                para "<?= htmlspecialchars($search) ?>"
                            <?php else: ?>
                                próximos à sua localização
                            <?php endif; ?>
                        </h3>
                    </div>
                    
                    <div class="stores-list">
                        <?php foreach ($brechos as $brecho): ?>
                            <div class="store-item">
                                <div class="store-icon">🏪</div>
                                
                                <div class="store-content">
                                    <h3 class="store-name"><?= htmlspecialchars($brecho['nome']) ?></h3>
                                    
                                    <div class="store-address">
                                        📍 <?= htmlspecialchars($brecho['endereco']) ?>
                                        <?php if ($brecho['cidade']): ?>
                                            - <?= htmlspecialchars($brecho['cidade']) ?>
                                        <?php endif; ?>
                                        <?php if ($brecho['estado']): ?>
                                            - <?= htmlspecialchars($brecho['estado']) ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($brecho['telefone']): ?>
                                        <div class="store-phone">
                                            📞 <?= htmlspecialchars($brecho['telefone']) ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($brecho['distancia'])): ?>
                                        <div class="store-distance">
                                            📍 <?= round($brecho['distancia'], 2) ?> km de distância
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($brecho['descricao']): ?>
                                        <div class="store-description">
                                            <?= htmlspecialchars($brecho['descricao']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">🏪</div>
                    <h3>Encontre brechós próximos</h3>
                    <p>Digite uma cidade ou use sua localização para descobrir brechós incríveis perto de você.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.search-stores-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Inter', sans-serif;
}

.search-stores-header {
    margin-bottom: 30px;
}

.search-stores-header h1 {
    color: #5e2b2b;
    margin-bottom: 10px;
}

.search-stores-header p {
    color: #666;
    margin-bottom: 20px;
}

.search-form {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
}

.form-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.search-input {
    flex: 1;
    min-width: 250px;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
}

.search-input:focus {
    outline: none;
    border-color: #5e2b2b;
}

.btn-search, .btn-location {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-search {
    background: #5e2b2b;
    color: white;
}

.btn-location {
    background: #28a745;
    color: white;
}

.btn-search:hover {
    background: #4a2222;
}

.btn-location:hover {
    background: #218838;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-results-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.results-header {
    margin-bottom: 20px;
}

.results-header h3 {
    color: #5e2b2b;
    font-size: 1.2rem;
}

.stores-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.store-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
}

.store-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.store-icon {
    font-size: 1.5rem;
    min-width: 40px;
    text-align: center;
}

.store-content {
    flex: 1;
}

.store-name {
    color: #5e2b2b;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 8px 0;
}

.store-address, .store-phone {
    color: #555;
    margin: 5px 0;
    font-size: 0.95rem;
}

.store-distance {
    background: #e8f5e8;
    color: #28a745;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
    margin: 8px 0;
}

.store-description {
    color: #666;
    margin-top: 10px;
    font-size: 0.9rem;
    line-height: 1.4;
}

@media (max-width: 768px) {
    .search-stores-page {
        padding: 15px;
    }
    
    .form-group {
        flex-direction: column;
    }
    
    .search-input {
        min-width: 100%;
    }
    
    .store-item {
        flex-direction: column;
        align-items: stretch;
    }
    
    .store-icon {
        align-self: flex-start;
    }
}
</style>

<script>
function getLocation() {
    if (navigator.geolocation) {
        document.getElementById('results').innerHTML = '<div class="no-results"><div class="no-results-icon">📍</div><h3>Obtendo sua localização...</h3></div>';
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            window.location.href = '?lat=' + lat + '&lng=' + lng;
        }, function(error) {
            alert('Erro ao obter localização: ' + error.message);
            document.getElementById('results').innerHTML = '<div class="no-results"><div class="no-results-icon">🏪</div><h3>Encontre brechós próximos</h3><p>Digite uma cidade ou use sua localização para descobrir brechós incríveis perto de você.</p></div>';
        });
    } else {
        alert('Geolocalização não suportada pelo navegador');
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>