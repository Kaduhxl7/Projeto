<?php
require_once __DIR__ . '/../../../includes/header.php';
?>

<main class="pix-container">
    <div class="pix-wrapper">
        <div class="pix-header">
            <h1>📱 Pagamento PIX</h1>
            <p>Escaneie o QR Code ou copie o código PIX</p>
        </div>

        <div class="pix-content">
            <div class="payment-info">
                <div class="info-item">
                    <span>Valor:</span>
                    <strong>R$ <?php echo number_format($data['valor'], 2, ',', '.'); ?></strong>
                </div>
                <div class="info-item">
                    <span>Código:</span>
                    <strong><?php echo $data['codigo_transacao']; ?></strong>
                </div>
                <div class="info-item">
                    <span>Expira em:</span>
                    <strong id="countdown">30:00</strong>
                </div>
            </div>

            <div class="qr-section">
                <div class="qr-placeholder">
                    <div class="qr-code">
                        <div class="qr-pattern"></div>
                        <span>QR CODE PIX</span>
                    </div>
                </div>
                <p>Escaneie com o app do seu banco</p>
            </div>

            <div class="pix-code-section">
                <label>Código PIX (Copia e Cola):</label>
                <div class="code-input-group">
                    <input type="text" id="pixCode" value="<?php echo $data['pix_code']; ?>" readonly>
                    <button onclick="copyPixCode()" class="copy-btn">📋 Copiar</button>
                </div>
            </div>

            <div class="status-section">
                <div id="paymentStatus" class="status-pending">
                    <span class="status-icon">⏳</span>
                    <span>Aguardando pagamento...</span>
                </div>
                <button onclick="checkPayment()" class="check-btn">🔄 Verificar Pagamento</button>
            </div>

            <div class="actions">
                <button onclick="history.back()" class="btn-secondary">← Voltar</button>
                <button onclick="window.location.href='index.php'" class="btn-primary">Ir para Home</button>
            </div>
        </div>
    </div>
</main>

<style>
.pix-container {
    max-width: 500px;
    margin: 40px auto;
    padding: 20px;
}

.pix-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.pix-header {
    background: linear-gradient(135deg, #32bcad, #00d4aa);
    color: white;
    padding: 30px;
    text-align: center;
}

.pix-content {
    padding: 30px;
}

.payment-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.qr-section {
    text-align: center;
    margin-bottom: 30px;
}

.qr-placeholder {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

.qr-code {
    width: 200px;
    height: 200px;
    border: 2px solid #32bcad;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    position: relative;
}

.qr-pattern {
    width: 150px;
    height: 150px;
    background: 
        linear-gradient(90deg, #32bcad 50%, transparent 50%),
        linear-gradient(#32bcad 50%, transparent 50%);
    background-size: 20px 20px;
    opacity: 0.3;
}

.qr-code span {
    position: absolute;
    bottom: 10px;
    font-size: 0.8rem;
    color: #32bcad;
    font-weight: bold;
}

.pix-code-section {
    margin-bottom: 30px;
}

.pix-code-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
}

.code-input-group {
    display: flex;
    gap: 10px;
}

.code-input-group input {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.9rem;
}

.copy-btn {
    padding: 12px 20px;
    background: #32bcad;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.copy-btn:hover {
    background: #2aa396;
}

.status-section {
    text-align: center;
    margin-bottom: 30px;
}

.status-pending, .status-success, .status-error {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-success {
    background: #d4edda;
    color: #155724;
}

.status-error {
    background: #f8d7da;
    color: #721c24;
}

.check-btn {
    padding: 12px 24px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.check-btn:hover {
    background: #0056b3;
}

.actions {
    display: flex;
    gap: 15px;
}

.btn-primary, .btn-secondary {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-primary {
    background: #5e2b2b;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .pix-container {
        margin: 20px;
        padding: 10px;
    }
    
    .code-input-group {
        flex-direction: column;
    }
    
    .actions {
        flex-direction: column;
    }
}
</style>

<script>
let paymentId = <?php echo $data['payment_id']; ?>;
let countdownTime = 30 * 60; // 30 minutos

// Countdown timer
function updateCountdown() {
    const minutes = Math.floor(countdownTime / 60);
    const seconds = countdownTime % 60;
    document.getElementById('countdown').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    if (countdownTime <= 0) {
        document.getElementById('paymentStatus').innerHTML = 
            '<span class="status-icon">⏰</span><span>Tempo expirado</span>';
        document.getElementById('paymentStatus').className = 'status-error';
        return;
    }
    
    countdownTime--;
    setTimeout(updateCountdown, 1000);
}

// Copiar código PIX
function copyPixCode() {
    const pixCode = document.getElementById('pixCode');
    pixCode.select();
    document.execCommand('copy');
    
    const btn = document.querySelector('.copy-btn');
    const originalText = btn.textContent;
    btn.textContent = '✅ Copiado!';
    setTimeout(() => {
        btn.textContent = originalText;
    }, 2000);
}

// Verificar pagamento
function checkPayment() {
    const btn = document.querySelector('.check-btn');
    btn.textContent = '🔄 Verificando...';
    btn.disabled = true;
    
    fetch('confirmar-pix.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'payment_id=' + paymentId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('paymentStatus').innerHTML = 
                '<span class="status-icon">✅</span><span>Pagamento confirmado!</span>';
            document.getElementById('paymentStatus').className = 'status-success';
            
            setTimeout(() => {
                window.location.href = 'pagamento-sucesso.php?id=' + paymentId;
            }, 2000);
        } else {
            document.getElementById('paymentStatus').innerHTML = 
                '<span class="status-icon">⏳</span><span>' + data.message + '</span>';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        btn.textContent = '🔄 Verificar Pagamento';
        btn.disabled = false;
    });
}

// Iniciar countdown
updateCountdown();

// Verificar pagamento automaticamente a cada 10 segundos
setInterval(() => {
    if (countdownTime > 0) {
        checkPayment();
    }
}, 10000);
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>