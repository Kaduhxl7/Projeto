<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
$page_title = 'Pagamento Aprovado - DressCode';
require_once '../includes/header.php';
?>

<main class="success-container">
    <div class="success-wrapper">
        <div class="success-icon">
            <div class="checkmark">✅</div>
        </div>
        
        <h1>Pagamento Aprovado!</h1>
        <p>Seu anúncio foi publicado com sucesso e já está disponível no site.</p>
        
        <div class="success-details">
            <div class="detail-item">
                <span>Status:</span>
                <strong class="status-paid">Pago</strong>
            </div>
            <div class="detail-item">
                <span>Data:</span>
                <strong><?php echo date('d/m/Y H:i'); ?></strong>
            </div>
            <?php if (isset($_GET['id'])): ?>
            <div class="detail-item">
                <span>ID do Pagamento:</span>
                <strong>#<?php echo htmlspecialchars($_GET['id']); ?></strong>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="success-actions">
            <a href="index.php" class="btn-primary">🏠 Ir para Home</a>
            <a href="dashboard.php" class="btn-secondary">📊 Ver Dashboard</a>
        </div>
        
        <div class="next-steps">
            <h3>📋 Próximos passos:</h3>
            <ul>
                <li>✅ Seu anúncio está ativo e visível para todos</li>
                <li>📧 Você receberá notificações sobre interesse no produto</li>
                <li>💬 Responda rapidamente às mensagens dos compradores</li>
                <li>📊 Acompanhe as visualizações no seu dashboard</li>
            </ul>
        </div>
    </div>
</main>

<style>
.success-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    text-align: center;
}

.success-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 40px;
}

.success-icon {
    margin-bottom: 30px;
}

.checkmark {
    font-size: 4rem;
    animation: bounceIn 0.6s ease;
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

.success-wrapper h1 {
    color: #28a745;
    margin-bottom: 15px;
    font-size: 2rem;
}

.success-wrapper p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.success-details {
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

.status-paid {
    color: #28a745;
}

.success-actions {
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

.next-steps {
    text-align: left;
    background: #e8f5e8;
    padding: 20px;
    border-radius: 8px;
}

.next-steps h3 {
    margin-bottom: 15px;
    color: #155724;
}

.next-steps ul {
    list-style: none;
    padding: 0;
}

.next-steps li {
    margin-bottom: 8px;
    color: #155724;
}

@media (max-width: 768px) {
    .success-container {
        margin: 20px;
        padding: 10px;
    }
    
    .success-wrapper {
        padding: 30px 20px;
    }
    
    .success-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Confetti effect
function createConfetti() {
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7'];
    
    for (let i = 0; i < 50; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                top: -10px;
                left: ${Math.random() * 100}vw;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                z-index: 1000;
                pointer-events: none;
                animation: fall 3s linear forwards;
            `;
            
            document.body.appendChild(confetti);
            
            setTimeout(() => {
                confetti.remove();
            }, 3000);
        }, i * 50);
    }
}

const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(360deg);
        }
    }
`;
document.head.appendChild(style);

// Trigger confetti on load
window.addEventListener('load', createConfetti);
</script>

<?php require_once '../includes/footer.php'; ?>