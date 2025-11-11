<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
$page_title = 'Erro no Pagamento - DressCode';
require_once '../includes/header.php';
?>

<main class="error-container">
    <div class="error-wrapper">
        <div class="error-icon">
            <div class="error-mark">❌</div>
        </div>
        
        <h1>Pagamento Não Aprovado</h1>
        <p>Infelizmente não foi possível processar seu pagamento. Tente novamente.</p>
        
        <div class="error-details">
            <div class="detail-item">
                <span>Status:</span>
                <strong class="status-failed">Falhou</strong>
            </div>
            <div class="detail-item">
                <span>Data:</span>
                <strong><?php echo date('d/m/Y H:i'); ?></strong>
            </div>
            <?php if (isset($_GET['id'])): ?>
            <div class="detail-item">
                <span>ID da Tentativa:</span>
                <strong>#<?php echo htmlspecialchars($_GET['id']); ?></strong>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="error-actions">
            <a href="javascript:history.back()" class="btn-primary">🔄 Tentar Novamente</a>
            <a href="index.php" class="btn-secondary">🏠 Ir para Home</a>
        </div>
        
        <div class="help-section">
            <h3>💡 Possíveis soluções:</h3>
            <ul>
                <li>🔍 Verifique os dados do cartão</li>
                <li>💰 Confirme se há saldo/limite disponível</li>
                <li>📱 Tente usar PIX como alternativa</li>
                <li>📞 Entre em contato com seu banco</li>
                <li>🕐 Aguarde alguns minutos e tente novamente</li>
            </ul>
        </div>
        
        <div class="support-section">
            <p>Precisa de ajuda? <a href="faq.php">Consulte nosso FAQ</a> ou entre em contato conosco.</p>
        </div>
    </div>
</main>

<style>
.error-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    text-align: center;
}

.error-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 40px;
}

.error-icon {
    margin-bottom: 30px;
}

.error-mark {
    font-size: 4rem;
    animation: shake 0.6s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.error-wrapper h1 {
    color: #dc3545;
    margin-bottom: 15px;
    font-size: 2rem;
}

.error-wrapper p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.error-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    text-align: left;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.status-failed {
    color: #dc3545;
}

.error-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
}

.btn-primary, .btn-secondary {
    flex: 1;
    padding: 15px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #5e2b2b;
    color: white;
}

.btn-primary:hover {
    background: #4a2222;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.help-section {
    text-align: left;
    background: #fff3cd;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.help-section h3 {
    margin-bottom: 15px;
    color: #856404;
}

.help-section ul {
    list-style: none;
    padding: 0;
}

.help-section li {
    margin-bottom: 8px;
    color: #856404;
}

.support-section {
    color: #6c757d;
    font-size: 0.9rem;
}

.support-section a {
    color: #5e2b2b;
    text-decoration: none;
}

.support-section a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .error-container {
        margin: 20px;
        padding: 10px;
    }
    
    .error-wrapper {
        padding: 30px 20px;
    }
    
    .error-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>