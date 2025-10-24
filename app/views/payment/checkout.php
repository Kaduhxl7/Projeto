<?php
require_once __DIR__ . '/../../../includes/header.php';
?>

<main class="checkout-container">
    <div class="checkout-wrapper">
        <div class="checkout-header">
            <h1>💳 Finalizar Pagamento</h1>
            <p>Taxa de publicação do anúncio</p>
        </div>

        <div class="checkout-content">
            <!-- Resumo do Produto -->
            <div class="product-summary">
                <h3>📦 Resumo do Anúncio</h3>
                <div class="product-info">
                    <strong><?php echo htmlspecialchars($data['produto']['nome']); ?></strong>
                    <p><?php echo htmlspecialchars(substr($data['produto']['descricao'], 0, 100)); ?>...</p>
                    <span class="price">R$ <?php echo number_format($data['produto']['preco'], 2, ',', '.'); ?></span>
                </div>
            </div>

            <!-- Resumo do Pagamento -->
            <div class="payment-summary">
                <h3>💰 Resumo do Pagamento</h3>
                <div class="payment-details">
                    <div class="payment-line">
                        <span>Taxa de publicação:</span>
                        <strong>R$ <?php echo number_format($data['taxa'], 2, ',', '.'); ?></strong>
                    </div>
                    <div class="payment-line total">
                        <span>Total a pagar:</span>
                        <strong>R$ <?php echo number_format($data['taxa'], 2, ',', '.'); ?></strong>
                    </div>
                </div>
            </div>

            <!-- Métodos de Pagamento -->
            <div class="payment-methods">
                <h3>🔒 Escolha o método de pagamento</h3>
                
                <form id="paymentForm" method="POST" action="processar-pagamento.php">
                    <input type="hidden" name="id_produto" value="<?php echo $data['produto']['id']; ?>">
                    
                    <?php if ($data['metodos']['pix']): ?>
                    <div class="payment-method">
                        <input type="radio" id="pix" name="metodo_pagamento" value="pix" checked>
                        <label for="pix">
                            <div class="method-info">
                                <span class="method-icon">📱</span>
                                <div>
                                    <strong>PIX</strong>
                                    <p>Pagamento instantâneo via PIX</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php endif; ?>

                    <?php if ($data['metodos']['cartao']): ?>
                    <div class="payment-method">
                        <input type="radio" id="cartao" name="metodo_pagamento" value="cartao">
                        <label for="cartao">
                            <div class="method-info">
                                <span class="method-icon">💳</span>
                                <div>
                                    <strong>Cartão de Crédito</strong>
                                    <p>Pagamento com cartão (simulado)</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php endif; ?>

                    <div class="checkout-actions">
                        <button type="button" onclick="history.back()" class="btn-secondary">
                            ← Voltar
                        </button>
                        <button type="submit" class="btn-primary">
                            Pagar R$ <?php echo number_format($data['taxa'], 2, ',', '.'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
.checkout-container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
}

.checkout-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.checkout-header {
    background: linear-gradient(135deg, #5e2b2b, #8b4444);
    color: white;
    padding: 30px;
    text-align: center;
}

.checkout-header h1 {
    margin: 0 0 10px 0;
    font-size: 1.8rem;
}

.checkout-content {
    padding: 30px;
}

.product-summary, .payment-summary, .payment-methods {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.product-summary:last-child, .payment-summary:last-child, .payment-methods:last-child {
    border-bottom: none;
}

.product-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
}

.product-info strong {
    display: block;
    margin-bottom: 5px;
    color: #5e2b2b;
}

.product-info .price {
    display: inline-block;
    background: #5e2b2b;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    margin-top: 10px;
}

.payment-details {
    margin-top: 10px;
}

.payment-line {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.payment-line.total {
    border-top: 1px solid #ddd;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 1.1rem;
}

.payment-method {
    margin-bottom: 15px;
}

.payment-method input[type="radio"] {
    display: none;
}

.payment-method label {
    display: block;
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method input[type="radio"]:checked + label {
    border-color: #5e2b2b;
    background: #f8f9fa;
}

.method-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.method-icon {
    font-size: 2rem;
}

.checkout-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-primary, .btn-secondary {
    flex: 1;
    padding: 15px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
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

@media (max-width: 768px) {
    .checkout-container {
        margin: 20px;
        padding: 10px;
    }
    
    .checkout-content {
        padding: 20px;
    }
    
    .checkout-actions {
        flex-direction: column;
    }
}
</style>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>