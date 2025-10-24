<?php
require_once __DIR__ . '/../../../includes/header.php';
?>

<main class="history-container">
    <div class="history-wrapper">
        <div class="history-header">
            <h1>💳 Histórico de Pagamentos</h1>
            <p>Acompanhe todos os seus pagamentos de taxa de anúncio</p>
        </div>

        <div class="history-content">
            <?php if (empty($data['pagamentos'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">📄</div>
                    <h3>Nenhum pagamento encontrado</h3>
                    <p>Você ainda não realizou nenhum pagamento de taxa de anúncio.</p>
                    <a href="index.php" class="btn-primary">Publicar Primeiro Anúncio</a>
                </div>
            <?php else: ?>
                <div class="payments-list">
                    <?php foreach ($data['pagamentos'] as $pagamento): ?>
                        <div class="payment-item">
                            <div class="payment-info">
                                <div class="payment-header">
                                    <strong class="payment-id">#<?php echo $pagamento['id']; ?></strong>
                                    <span class="payment-status status-<?php echo $pagamento['status_pagamento']; ?>">
                                        <?php
                                        $status_icons = [
                                            'pendente' => '⏳',
                                            'pago' => '✅',
                                            'falhou' => '❌',
                                            'cancelado' => '🚫'
                                        ];
                                        echo $status_icons[$pagamento['status_pagamento']] ?? '❓';
                                        echo ' ' . ucfirst($pagamento['status_pagamento']);
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="payment-details">
                                    <?php if ($pagamento['produto_nome']): ?>
                                        <div class="detail-row">
                                            <span class="label">Produto:</span>
                                            <span class="value"><?php echo htmlspecialchars($pagamento['produto_nome']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="detail-row">
                                        <span class="label">Valor:</span>
                                        <span class="value price">R$ <?php echo number_format($pagamento['valor'], 2, ',', '.'); ?></span>
                                    </div>
                                    
                                    <div class="detail-row">
                                        <span class="label">Método:</span>
                                        <span class="value">
                                            <?php
                                            $metodos = [
                                                'pix' => '📱 PIX',
                                                'cartao' => '💳 Cartão',
                                                'boleto' => '📄 Boleto'
                                            ];
                                            echo $metodos[$pagamento['metodo_pagamento']] ?? $pagamento['metodo_pagamento'];
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="detail-row">
                                        <span class="label">Data:</span>
                                        <span class="value"><?php echo date('d/m/Y H:i', strtotime($pagamento['data_criacao'])); ?></span>
                                    </div>
                                    
                                    <?php if ($pagamento['data_pagamento']): ?>
                                        <div class="detail-row">
                                            <span class="label">Pago em:</span>
                                            <span class="value"><?php echo date('d/m/Y H:i', strtotime($pagamento['data_pagamento'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($pagamento['codigo_transacao']): ?>
                                        <div class="detail-row">
                                            <span class="label">Código:</span>
                                            <span class="value code"><?php echo htmlspecialchars($pagamento['codigo_transacao']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="payment-actions">
                                <?php if ($pagamento['status_pagamento'] === 'pendente'): ?>
                                    <button onclick="checkPaymentStatus(<?php echo $pagamento['id']; ?>)" class="btn-check">
                                        🔄 Verificar
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($pagamento['status_pagamento'] === 'pago' && $pagamento['id_produto']): ?>
                                    <a href="produto.php?id=<?php echo $pagamento['id_produto']; ?>" class="btn-view">
                                        👁️ Ver Produto
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="history-summary">
                    <h3>📊 Resumo</h3>
                    <div class="summary-stats">
                        <?php
                        $total_pagamentos = count($data['pagamentos']);
                        $pagamentos_pagos = array_filter($data['pagamentos'], fn($p) => $p['status_pagamento'] === 'pago');
                        $total_pago = array_sum(array_map(fn($p) => $p['valor'], $pagamentos_pagos));
                        ?>
                        <div class="stat-item">
                            <span class="stat-label">Total de pagamentos:</span>
                            <span class="stat-value"><?php echo $total_pagamentos; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pagamentos aprovados:</span>
                            <span class="stat-value"><?php echo count($pagamentos_pagos); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total pago:</span>
                            <span class="stat-value price">R$ <?php echo number_format($total_pago, 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.history-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.history-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.history-header {
    background: linear-gradient(135deg, #5e2b2b, #8b4444);
    color: white;
    padding: 30px;
    text-align: center;
}

.history-content {
    padding: 30px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin-bottom: 10px;
    color: #6c757d;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 30px;
}

.payments-list {
    margin-bottom: 30px;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.payment-item:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.payment-info {
    flex: 1;
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.payment-id {
    font-size: 1.1rem;
    color: #5e2b2b;
}

.payment-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-pendente {
    background: #fff3cd;
    color: #856404;
}

.status-pago {
    background: #d4edda;
    color: #155724;
}

.status-falhou {
    background: #f8d7da;
    color: #721c24;
}

.status-cancelado {
    background: #e2e3e5;
    color: #6c757d;
}

.payment-details {
    display: grid;
    gap: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
}

.label {
    color: #6c757d;
    font-weight: 500;
}

.value {
    font-weight: 600;
}

.value.price {
    color: #28a745;
}

.value.code {
    font-family: monospace;
    font-size: 0.9rem;
}

.payment-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-left: 20px;
}

.btn-check, .btn-view {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-check {
    background: #007bff;
    color: white;
}

.btn-check:hover {
    background: #0056b3;
}

.btn-view {
    background: #28a745;
    color: white;
}

.btn-view:hover {
    background: #1e7e34;
}

.history-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.summary-stats {
    display: grid;
    gap: 10px;
    margin-top: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
}

.stat-label {
    color: #6c757d;
}

.stat-value {
    font-weight: 600;
}

.btn-primary {
    display: inline-block;
    padding: 15px 30px;
    background: #5e2b2b;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #4a2222;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .history-container {
        margin: 20px;
        padding: 10px;
    }
    
    .history-content {
        padding: 20px;
    }
    
    .payment-item {
        flex-direction: column;
        gap: 15px;
    }
    
    .payment-actions {
        margin-left: 0;
        flex-direction: row;
    }
    
    .payment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<script>
function checkPaymentStatus(paymentId) {
    const btn = event.target;
    const originalText = btn.textContent;
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
            location.reload(); // Recarregar para mostrar status atualizado
        } else {
            alert(data.message || 'Pagamento ainda não confirmado');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao verificar pagamento');
    })
    .finally(() => {
        btn.textContent = originalText;
        btn.disabled = false;
    });
}
</script>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>