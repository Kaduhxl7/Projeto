<?php
require_once __DIR__ . '/../config/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="<?= getCurrentLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($brecho['nome']) ?> - DressCode</title>
    <link rel="stylesheet" href="/Projeto/Projeto-master/public/assets/css/style.css">
    <style>
        .brecho-details {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .brecho-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .brecho-name {
            font-size: 2rem;
            color: #5e2b2b;
            margin-bottom: 15px;
        }
        
        .brecho-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-icon {
            font-size: 1.2rem;
        }
        
        .brecho-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .brecho-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #5e2b2b;
            color: white;
        }
        
        .btn-secondary {
            background: #007bff;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .map-container {
            margin-top: 30px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .map-container iframe {
            width: 100%;
            height: 300px;
            border: none;
        }
        
        @media (max-width: 768px) {
            .brecho-actions {
                flex-direction: column;
            }
            
            .btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    
    <main class="brecho-details">
        <div class="brecho-header">
            <h1 class="brecho-name"><?= htmlspecialchars($brecho['nome']) ?></h1>
            
            <div class="brecho-info">
                <?php if (!empty($brecho['endereco'])): ?>
                    <div class="info-item">
                        <span class="info-icon">📍</span>
                        <div>
                            <strong>Endereço:</strong><br>
                            <?= htmlspecialchars($brecho['endereco']) ?>
                            <?php if (!empty($brecho['cidade'])): ?>
                                <br><?= htmlspecialchars($brecho['cidade']) ?>
                                <?php if (!empty($brecho['estado'])): ?>
                                    - <?= htmlspecialchars($brecho['estado']) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($brecho['telefone'])): ?>
                    <div class="info-item">
                        <span class="info-icon">📞</span>
                        <div>
                            <strong>Telefone:</strong><br>
                            <a href="tel:<?= htmlspecialchars($brecho['telefone']) ?>">
                                <?= htmlspecialchars($brecho['telefone']) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($brecho['email'])): ?>
                    <div class="info-item">
                        <span class="info-icon">✉️</span>
                        <div>
                            <strong>E-mail:</strong><br>
                            <a href="mailto:<?= htmlspecialchars($brecho['email']) ?>">
                                <?= htmlspecialchars($brecho['email']) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($brecho['descricao'])): ?>
                <div class="brecho-description">
                    <h3>Sobre o Brechó</h3>
                    <p><?= nl2br(htmlspecialchars($brecho['descricao'])) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="brecho-actions">
                <a href="buscar.php" class="btn btn-primary">
                    ← Voltar à Busca
                </a>
                
                <?php if (!empty($brecho['telefone'])): ?>
                    <a href="tel:<?= htmlspecialchars($brecho['telefone']) ?>" class="btn btn-success">
                        📞 Ligar
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($brecho['email'])): ?>
                    <a href="mailto:<?= htmlspecialchars($brecho['email']) ?>" class="btn btn-secondary">
                        ✉️ Enviar E-mail
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($brecho['latitude']) && !empty($brecho['longitude'])): ?>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $brecho['latitude'] ?>,<?= $brecho['longitude'] ?>" 
                       target="_blank" class="btn btn-secondary">
                        🗺️ Traçar Rota
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($brecho['latitude']) && !empty($brecho['longitude'])): ?>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=<?= $brecho['latitude'] ?>,<?= $brecho['longitude'] ?>&zoom=15"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Localização do <?= htmlspecialchars($brecho['nome']) ?>">
                </iframe>
            </div>
        <?php endif; ?>
    </main>
    
    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>