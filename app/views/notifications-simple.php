<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="notifications-page">
    <div class="notifications-header">
        <div class="header-content">
            <div class="header-text">
                <h1><?php echo __('notifications.title'); ?></h1>
                <p><?php echo __('notifications.description'); ?></p>
            </div>
            
            <?php if (!empty($data['notifications'])): ?>
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $data['stats']['total']; ?></span>
                        <span class="stat-label">Total</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number unread"><?php echo $data['stats']['nao_lidas']; ?></span>
                        <span class="stat-label">Não lidas</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="notifications-actions">
            <?php if (!empty($data['notifications'])): ?>
                <button onclick="markAllAsRead()" class="btn btn-secondary">
                    <span class="btn-icon">✓</span>
                    <?php echo __('notifications.mark_all_read'); ?>
                </button>
                <button onclick="deleteAllNotifications()" class="btn btn-danger">
                    <span class="btn-icon">🗑️</span>
                    Excluir Todas
                </button>
            <?php endif; ?>
            
            <button onclick="openPromotionModal()" class="btn btn-primary">
                <span class="btn-icon">📢</span>
                Enviar Promoção
            </button>
        </div>
    </div>

    <div class="notifications-container">
        <?php if (empty($data['notifications'])): ?>
            <div class="empty-state">
                <div class="empty-icon">🔔</div>
                <h3><?php echo __('notifications.no_notifications'); ?></h3>
                <p><?php echo __('notifications.no_notifications_message'); ?></p>
                <div class="empty-actions">
                    <a href="index.php" class="btn btn-primary">Explorar Produtos</a>
                </div>
            </div>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach ($data['notifications'] as $notification): ?>
                    <div class="notification-card <?php echo $notification['lida'] ? 'read' : 'unread'; ?>" 
                         data-id="<?php echo $notification['id']; ?>"
                         data-type="<?php echo $notification['tipo']; ?>">
                        
                        <div class="notification-icon">
                            <?php
                            switch($notification['tipo']) {
                                case 'novo_produto':
                                    echo '📦';
                                    break;
                                case 'promocao':
                                    echo '💸';
                                    break;
                                case 'atualizacao_brecho':
                                    echo '🏪';
                                    break;
                                default:
                                    echo '🔔';
                            }
                            ?>
                        </div>
                        
                        <div class="notification-content">
                            <div class="notification-header">
                                <h3 class="notification-title"><?php echo htmlspecialchars($notification['titulo']); ?></h3>
                                <div class="notification-meta">
                                    <span class="notification-type">
                                        <?php 
                                        switch($notification['tipo']) {
                                            case 'novo_produto': echo 'Novo Produto'; break;
                                            case 'promocao': echo 'Promoção'; break;
                                            case 'atualizacao_brecho': echo 'Atualização'; break;
                                        }
                                        ?>
                                    </span>
                                    <span class="notification-date"><?php echo date('d/m/Y H:i', strtotime($notification['data_envio'])); ?></span>
                                </div>
                            </div>
                            
                            <p class="notification-message"><?php echo htmlspecialchars($notification['mensagem']); ?></p>
                            
                            <?php if ($notification['produto_id'] && !empty($notification['produto_nome'])): ?>
                                <div class="notification-product">
                                    <div class="product-info">
                                        <span class="product-name"><?php echo htmlspecialchars($notification['produto_nome']); ?></span>
                                    </div>
                                    <a href="produto.php?id=<?php echo $notification['produto_id']; ?>" 
                                       class="btn btn-sm btn-outline">
                                        Ver Produto
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($notification['brecho_nome'])): ?>
                                <div class="notification-store">
                                    <span class="store-icon">🏪</span>
                                    <span class="store-name"><?php echo htmlspecialchars($notification['brecho_nome']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="notification-actions">
                            <?php if (!$notification['lida']): ?>
                                <button onclick="markAsRead(<?php echo $notification['id']; ?>)" 
                                        class="btn-mark-read" 
                                        title="Marcar como lida">
                                    ✓
                                </button>
                            <?php endif; ?>
                            <button onclick="deleteNotification(<?php echo $notification['id']; ?>)" 
                                    class="btn-delete" 
                                    title="Excluir notificação">
                                🗑️
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal Moderno para envio de promoções -->
<div id="promotionModal" class="modern-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closePromotionModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon">
                <div class="icon-circle">
                    <span class="icon">📢</span>
                </div>
            </div>
            <h2 class="modal-title">Criar Nova Promoção</h2>
            <p class="modal-subtitle">Envie uma notificação especial para todos os seus clientes</p>
            <button onclick="closePromotionModal()" class="modal-close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        
        <form id="promotionForm" onsubmit="sendPromotion(event)" class="modal-form">
            <div class="form-section">
                <div class="input-group">
                    <label for="promotionTitle" class="input-label">
                        <span class="label-icon">🏷️</span>
                        Título da Promoção
                    </label>
                    <input type="text" id="promotionTitle" name="titulo" required 
                           class="modern-input"
                           placeholder="Ex: Desconto de 50% em toda a coleção">
                </div>
                
                <div class="input-group">
                    <label for="promotionDescription" class="input-label">
                        <span class="label-icon">📝</span>
                        Descrição da Promoção
                    </label>
                    <textarea id="promotionDescription" name="descricao" required 
                              class="modern-textarea"
                              placeholder="Descreva os detalhes da promoção, validade, condições especiais..."
                              rows="4"></textarea>
                </div>
                
                <div class="promotion-preview">
                    <div class="preview-header">
                        <span class="preview-icon">👀</span>
                        <span>Prévia da Notificação</span>
                    </div>
                    <div class="preview-card">
                        <div class="preview-notification">
                            <div class="notification-icon-preview">💸</div>
                            <div class="notification-content-preview">
                                <h4 id="previewTitle">Nova promoção no brechó!</h4>
                                <p id="previewDescription">Sua descrição aparecerá aqui...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closePromotionModal()" class="btn-cancel">
                    <span class="btn-icon">❌</span>
                    Cancelar
                </button>
                <button type="submit" class="btn-send">
                    <span class="btn-loading" style="display: none;">
                        <div class="spinner"></div>
                    </span>
                    <span class="btn-icon">🚀</span>
                    <span class="btn-text">Enviar Promoção</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modais de Confirmação -->
<div id="confirmModal" class="confirm-modal" style="display: none;">
    <div class="confirm-backdrop"></div>
    <div class="confirm-container">
        <div class="confirm-icon">
            <div class="warning-circle">
                <span class="warning-icon">⚠️</span>
            </div>
        </div>
        <div class="confirm-content">
            <h3 class="confirm-title" id="confirmTitle">Confirmar Ação</h3>
            <p class="confirm-message" id="confirmMessage">Tem certeza que deseja continuar?</p>
        </div>
        <div class="confirm-actions">
            <button onclick="closeConfirmModal()" class="btn-confirm-cancel">
                <span class="btn-icon">❌</span>
                Cancelar
            </button>
            <button id="confirmButton" class="btn-confirm-delete">
                <span class="btn-icon">🗑️</span>
                <span class="btn-text">Excluir</span>
            </button>
        </div>
    </div>
</div>

<style>
.notifications-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Inter', sans-serif;
}

.notifications-header {
    margin-bottom: 30px;
    padding: 25px;
    background: linear-gradient(135deg, #5e2b2b 0%, #4a2222 100%);
    border-radius: 16px;
    color: white;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.header-text h1 {
    margin: 0 0 8px 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.header-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.header-stats {
    display: flex;
    gap: 20px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 4px;
}

.stat-number.unread {
    color: #ffc107;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.8;
}

.notifications-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: white;
    color: #5e2b2b;
}

.btn-primary:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
}

.btn-secondary {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.3);
}

.btn-danger {
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
    background: #dc3545;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #5e2b2b;
    border: 1px solid #5e2b2b;
}

.btn-outline:hover {
    background: #5e2b2b;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-icon {
    font-size: 1rem;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #666;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 12px;
    font-size: 1.3rem;
}

.empty-state p {
    margin-bottom: 30px;
    line-height: 1.6;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.notification-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 16px;
    transition: all 0.3s ease;
    position: relative;
}

.notification-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.notification-card.unread {
    border-left: 4px solid #5e2b2b;
    background: linear-gradient(90deg, #fefefe 0%, #ffffff 100%);
}

.notification-card.unread::before {
    content: '';
    position: absolute;
    top: 12px;
    right: 12px;
    width: 8px;
    height: 8px;
    background: #dc3545;
    border-radius: 50%;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    background: #f8f9fa;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.notification-title {
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    line-height: 1.3;
}

.notification-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    flex-shrink: 0;
    margin-left: 12px;
}

.notification-type {
    background: #e9ecef;
    color: #495057;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.notification-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.notification-message {
    color: #555;
    line-height: 1.5;
    margin: 0 0 16px 0;
}

.notification-product {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 12px;
}

.product-info {
    flex: 1;
    min-width: 0;
}

.product-name {
    display: block;
    font-weight: 500;
    color: #333;
    margin-bottom: 2px;
}

.notification-store {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #e3f2fd;
    border-radius: 6px;
    font-size: 0.9rem;
}

.store-icon {
    font-size: 1rem;
}

.store-name {
    font-weight: 500;
    color: #1976d2;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    gap: 8px;
}

.btn-mark-read {
    width: 36px;
    height: 36px;
    border: none;
    background: #28a745;
    color: white;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-mark-read:hover {
    background: #218838;
    transform: scale(1.1);
}

.btn-delete {
    width: 36px;
    height: 36px;
    border: none;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    margin-top: 8px;
}

.btn-delete:hover {
    background: #c82333;
    transform: scale(1.1);
}

/* Modal Moderno */
.modern-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modern-modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(94, 43, 43, 0.8), rgba(0, 0, 0, 0.6));
    backdrop-filter: blur(10px);
}

.modal-container {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
    position: relative;
}

.modern-modal.show .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    background: linear-gradient(135deg, #5e2b2b 0%, #4a2222 100%);
    color: white;
    padding: 30px;
    text-align: center;
    position: relative;
}

.modal-icon {
    margin-bottom: 15px;
}

.icon-circle {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.icon-circle .icon {
    font-size: 2.5rem;
}

.modal-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 8px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.modal-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.modal-form {
    padding: 0;
}

.form-section {
    padding: 30px;
}

.input-group {
    margin-bottom: 25px;
}

.input-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.label-icon {
    font-size: 1.1rem;
}

.modern-input,
.modern-textarea {
    width: 100%;
    padding: 15px 18px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.modern-input:focus,
.modern-textarea:focus {
    outline: none;
    border-color: #5e2b2b;
    background: white;
    box-shadow: 0 0 0 3px rgba(94, 43, 43, 0.1);
    transform: translateY(-1px);
}

.modern-textarea {
    resize: vertical;
    min-height: 100px;
    line-height: 1.5;
}

.promotion-preview {
    margin-top: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    border: 1px solid #dee2e6;
}

.preview-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.preview-icon {
    font-size: 1.1rem;
}

.preview-card {
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.preview-notification {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.notification-icon-preview {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.notification-content-preview {
    flex: 1;
}

.notification-content-preview h4 {
    margin: 0 0 5px 0;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

.notification-content-preview p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
    line-height: 1.4;
}

.modal-footer {
    padding: 25px 30px;
    background: #f8f9fa;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    border-top: 1px solid #e9ecef;
}

.btn-cancel,
.btn-send {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-cancel {
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-send {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    position: relative;
    overflow: hidden;
}

.btn-send:hover {
    background: linear-gradient(135deg, #218838, #1ea080);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-send:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-icon {
    font-size: 1rem;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Modal de Confirmação Moderno */
.confirm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.confirm-modal.show {
    opacity: 1;
    visibility: visible;
}

.confirm-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.8), rgba(0, 0, 0, 0.7));
    backdrop-filter: blur(15px);
}

.confirm-container {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 450px;
    padding: 0;
    box-shadow: 0 25px 50px rgba(220, 53, 69, 0.3);
    transform: scale(0.8) translateY(30px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.confirm-modal.show .confirm-container {
    transform: scale(1) translateY(0);
}

.confirm-icon {
    text-align: center;
    padding: 30px 30px 20px 30px;
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.warning-circle {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.warning-icon {
    font-size: 2.5rem;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.confirm-content {
    padding: 25px 30px;
    text-align: center;
}

.confirm-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 12px 0;
}

.confirm-message {
    font-size: 1rem;
    color: #666;
    line-height: 1.5;
    margin: 0;
}

.confirm-actions {
    padding: 20px 30px 30px 30px;
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn-confirm-cancel,
.btn-confirm-delete {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
    justify-content: center;
}

.btn-confirm-cancel {
    background: #6c757d;
    color: white;
}

.btn-confirm-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-confirm-delete {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    position: relative;
    overflow: hidden;
}

.btn-confirm-delete:hover {
    background: linear-gradient(135deg, #c82333, #a71e2a);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
}

.btn-confirm-delete:active {
    transform: translateY(0);
}

/* Animação de entrada */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.8);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsividade para o modal de confirmação */
@media (max-width: 768px) {
    .confirm-container {
        margin: 20px;
        width: calc(100% - 40px);
    }
    
    .confirm-icon {
        padding: 25px 20px 15px 20px;
    }
    
    .warning-circle {
        width: 60px;
        height: 60px;
    }
    
    .warning-icon {
        font-size: 2rem;
    }
    
    .confirm-content {
        padding: 20px 25px;
    }
    
    .confirm-title {
        font-size: 1.3rem;
    }
    
    .confirm-actions {
        padding: 15px 25px 25px 25px;
        flex-direction: column;
    }
    
    .btn-confirm-cancel,
    .btn-confirm-delete {
        width: 100%;
    }
}

.btn-loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .notifications-page {
        padding: 15px;
    }
    
    .notifications-header {
        padding: 20px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 16px;
    }
    
    .header-stats {
        align-self: stretch;
        justify-content: space-around;
    }
    
    .notifications-actions {
        justify-content: stretch;
    }
    
    .btn {
        flex: 1;
        justify-content: center;
    }
    
    .notification-card {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
    }
    
    .notification-header {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .notification-meta {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
    
    .notification-actions {
        flex-direction: row;
        justify-content: flex-end;
    }
    
    .notification-product {
        flex-wrap: wrap;
    }
    
    .modal-container {
        margin: 20px;
        width: calc(100% - 40px);
        max-height: calc(100vh - 40px);
        overflow-y: auto;
    }
    
    .modal-header {
        padding: 25px 20px;
    }
    
    .modal-title {
        font-size: 1.5rem;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
    }
    
    .icon-circle .icon {
        font-size: 2rem;
    }
    
    .form-section {
        padding: 25px 20px;
    }
    
    .modal-footer {
        padding: 20px;
        flex-direction: column;
    }
    
    .btn-cancel,
    .btn-send {
        width: 100%;
        justify-content: center;
    }
    
    .promotion-preview {
        margin-top: 20px;
        padding: 15px;
    }
    
    .modern-input,
    .modern-textarea {
        padding: 12px 15px;
        font-size: 16px; /* Evita zoom no iOS */
    }
}
</style>

<script>
// Marcar notificação como lida
function markAsRead(notificationId) {
    fetch('notifications.php?action=mark_read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            if (item) {
                item.classList.remove('unread');
                item.classList.add('read');
                
                const button = item.querySelector('.btn-mark-read');
                if (button) {
                    button.remove();
                }
                
                updateNotificationCount();
            }
        }
    })
    .catch(error => console.error('Erro:', error));
}

// Marcar todas como lidas
function markAllAsRead() {
    showConfirmModal(
        'Marcar como Lidas',
        'Marcar todas as notificações como lidas?',
        'Marcar',
        () => {
            fetch('notifications.php?action=mark_all_read', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-card.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                        
                        const button = item.querySelector('.btn-mark-read');
                        if (button) {
                            button.remove();
                        }
                    });
                    
                    updateNotificationCount();
                    location.reload();
                }
            })
            .catch(error => console.error('Erro:', error));
        }
    );
}

// Excluir notificação individual
function deleteNotification(notificationId) {
    showConfirmModal(
        'Excluir Notificação',
        'Esta notificação será excluída permanentemente. Deseja continuar?',
        'Excluir',
        () => {
            fetch('notifications.php?action=delete_notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: notificationId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-id="${notificationId}"]`);
                    if (item) {
                        item.style.transform = 'translateX(100%)';
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.remove();
                            updateNotificationCount();
                            
                            // Verificar se não há mais notificações
                            if (document.querySelectorAll('.notification-card').length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                } else {
                    alert('Erro ao excluir notificação');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao excluir notificação');
            });
        }
    );
}

// Excluir todas as notificações
function deleteAllNotifications() {
    showConfirmModal(
        'Excluir Todas as Notificações',
        'TODAS as notificações serão excluídas permanentemente. Esta ação não pode ser desfeita!',
        'Excluir Todas',
        () => {
            fetch('notifications.php?action=delete_all', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animar saída de todas as notificações
                    document.querySelectorAll('.notification-card').forEach((item, index) => {
                        setTimeout(() => {
                            item.style.transform = 'translateX(100%)';
                            item.style.opacity = '0';
                        }, index * 100);
                    });
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Erro ao excluir notificações');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao excluir notificações');
            });
        }
    );
}

// Atualizar contador no header
function updateNotificationCount() {
    const counter = document.getElementById('notificationCount');
    if (counter) {
        fetch('notifications.php?action=get_notifications')
            .then(response => response.json())
            .then(data => {
                if (data.unread_count > 0) {
                    counter.textContent = data.unread_count;
                    counter.style.display = 'flex';
                } else {
                    counter.style.display = 'none';
                }
            });
    }
}

// Modal de promoção moderno
function openPromotionModal() {
    const modal = document.getElementById('promotionModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Animação de entrada
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
    
    // Focar no primeiro campo
    setTimeout(() => {
        document.getElementById('promotionTitle').focus();
    }, 300);
}

function closePromotionModal() {
    const modal = document.getElementById('promotionModal');
    modal.classList.remove('show');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('promotionForm').reset();
        updatePreview();
    }, 300);
}

// Prévia em tempo real
function updatePreview() {
    const title = document.getElementById('promotionTitle').value || 'Nova promoção no brechó!';
    const description = document.getElementById('promotionDescription').value || 'Sua descrição aparecerá aqui...';
    
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewDescription').textContent = description;
}

// Adicionar listeners para prévia em tempo real
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('promotionTitle');
    const descInput = document.getElementById('promotionDescription');
    
    if (titleInput && descInput) {
        titleInput.addEventListener('input', updatePreview);
        descInput.addEventListener('input', updatePreview);
    }
});

// Enviar promoção
function sendPromotion(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = {
        titulo: formData.get('titulo'),
        descricao: formData.get('descricao')
    };
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const loadingIcon = submitBtn.querySelector('.btn-loading');
    const btnText = submitBtn.querySelector('.btn-text');
    
    // Mostrar loading
    loadingIcon.style.display = 'inline';
    btnText.textContent = 'Enviando...';
    submitBtn.disabled = true;
    
    fetch('notifications.php?action=send_promotion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Notificação enviada com sucesso! (${data.notifications_sent} usuários)`);
            closePromotionModal();
            location.reload();
        } else {
            alert('Erro ao enviar notificação: ' + (data.error || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar notificação');
    })
    .finally(() => {
        // Esconder loading
        loadingIcon.style.display = 'none';
        btnText.textContent = 'Enviar Promoção';
        submitBtn.disabled = false;
    });
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(event) {
    const modal = document.getElementById('promotionModal');
    if (event.target === modal) {
        closePromotionModal();
    }
});

// Funções do Modal de Confirmação
function showConfirmModal(title, message, buttonText, onConfirm) {
    const modal = document.getElementById('confirmModal');
    const titleEl = document.getElementById('confirmTitle');
    const messageEl = document.getElementById('confirmMessage');
    const buttonEl = document.getElementById('confirmButton');
    const buttonTextEl = buttonEl.querySelector('.btn-text');
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    buttonTextEl.textContent = buttonText;
    
    // Remover listeners anteriores
    const newButton = buttonEl.cloneNode(true);
    buttonEl.parentNode.replaceChild(newButton, buttonEl);
    
    // Adicionar novo listener
    newButton.addEventListener('click', () => {
        closeConfirmModal();
        onConfirm();
    });
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.remove('show');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 300);
}

// Fechar modal ao clicar no backdrop
document.addEventListener('click', function(event) {
    const modal = document.getElementById('confirmModal');
    const backdrop = modal.querySelector('.confirm-backdrop');
    
    if (event.target === backdrop) {
        closeConfirmModal();
    }
});

// Auto-refresh a cada 2 minutos
setInterval(function() {
    updateNotificationCount();
}, 120000);
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>