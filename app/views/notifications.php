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
                        <span class="stat-label"><?php echo __('notifications.no_notifications'); ?></span>
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
            <?php endif; ?>
            
            <button onclick="openPromotionModal()" class="btn btn-primary">
                <span class="btn-icon">📢</span>
                <?php echo __('notifications.notify_customers'); ?>
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
                        
                        <div class="notification-icon" style="background-color: <?php echo $notification['color'] ?? '#6c757d'; ?>20;">
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
                                    <span class="notification-type"><?php echo __('notifications.type_' . $notification['tipo']); ?></span>
                                    <span class="notification-date"><?php echo date('d/m/Y H:i', strtotime($notification['data_envio'])); ?></span>
                                </div>
                            </div>
                            
                            <p class="notification-message"><?php echo htmlspecialchars($notification['mensagem']); ?></p>
                            
                            <?php if ($notification['produto_id'] && $notification['produto_nome']): ?>
                                <div class="notification-product">
                                    <?php if ($notification['produto_imagem']): ?>
                                        <img src="assets/images/<?php echo htmlspecialchars($notification['produto_imagem']); ?>" 
                                             alt="<?php echo htmlspecialchars($notification['produto_nome']); ?>" 
                                             class="product-thumb">
                                    <?php endif; ?>
                                    <div class="product-info">
                                        <span class="product-name"><?php echo htmlspecialchars($notification['produto_nome']); ?></span>
                                        <?php if ($notification['produto_preco']): ?>
                                            <span class="product-price">R$ <?php echo number_format($notification['produto_preco'], 2, ',', '.'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="produto.php?id=<?php echo $notification['produto_id']; ?>" 
                                       class="btn btn-sm btn-outline">
                                        <?php echo __('notifications.view_product'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($notification['brecho_nome'] && $notification['tipo'] !== 'novo_produto'): ?>
                                <div class="notification-store">
                                    <span class="store-icon">🏪</span>
                                    <span class="store-name"><?php echo htmlspecialchars($notification['brecho_nome']); ?></span>
                                    <?php if ($notification['brecho_cidade']): ?>
                                        <span class="store-location"><?php echo htmlspecialchars($notification['brecho_cidade']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="notification-actions">
                            <?php if (!$notification['lida']): ?>
                                <button onclick="markAsRead(<?php echo $notification['id']; ?>)" 
                                        class="btn-mark-read" 
                                        title="<?php echo __('notifications.mark_as_read'); ?>">
                                    <span class="sr-only"><?php echo __('notifications.mark_as_read'); ?></span>
                                    ✓
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal para envio de promoções -->
<div id="promotionModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><?php echo __('notifications.send_notification'); ?></h3>
            <button onclick="closePromotionModal()" class="modal-close">&times;</button>
        </div>
        <form id="promotionForm" onsubmit="sendPromotion(event)">
            <div class="form-group">
                <label for="promotionTitle"><?php echo __('notifications.promotion_subject'); ?></label>
                <input type="text" id="promotionTitle" name="titulo" required 
                       placeholder="Ex: Desconto de 30% em toda loja">
            </div>
            <div class="form-group">
                <label for="promotionDescription"><?php echo __('notifications.promotion_description'); ?></label>
                <textarea id="promotionDescription" name="descricao" required 
                          placeholder="Descreva os detalhes da promoção..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closePromotionModal()" class="btn btn-secondary">
                    <?php echo __('notifications.cancel'); ?>
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-loading" style="display: none;">⏳</span>
                    <span class="btn-text"><?php echo __('notifications.send_promotion'); ?></span>
                </button>
            </div>
        </form>
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

.product-thumb {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
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

.product-price {
    display: block;
    color: #5e2b2b;
    font-weight: 600;
    font-size: 0.9rem;
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

.store-location {
    color: #666;
    font-size: 0.8rem;
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

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.modal-close:hover {
    background: #f8f9fa;
}

.form-group {
    margin-bottom: 20px;
    padding: 0 24px;
}

.form-group:first-of-type {
    padding-top: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e9ecef;
    justify-content: flex-end;
}

.btn-loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0,0,0,0);
    white-space: nowrap;
    border: 0;
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
    
    .modal-content {
        margin: 20px;
        width: calc(100% - 40px);
    }
}

/* Dark Theme Support */
@media (prefers-color-scheme: dark) {
    .notification-card {
        background: #2d2d2d;
        border-color: #404040;
        color: #fff;
    }
    
    .notification-title {
        color: #fff;
    }
    
    .notification-message {
        color: #ccc;
    }
    
    .notification-product {
        background: #404040;
    }
    
    .product-name {
        color: #fff;
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
                updateHeaderStats();
            }
        }
    })
    .catch(error => console.error('Erro:', error));
}

// Marcar todas como lidas
function markAllAsRead() {
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
            updateHeaderStats();
            
            // Esconder botão se não há mais não lidas
            const markAllBtn = document.querySelector('.notifications-actions .btn-secondary');
            if (markAllBtn) {
                markAllBtn.style.display = 'none';
            }
        }
    })
    .catch(error => console.error('Erro:', error));
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

// Atualizar estatísticas no cabeçalho
function updateHeaderStats() {
    const unreadStat = document.querySelector('.stat-number.unread');
    if (unreadStat) {
        const currentUnread = parseInt(unreadStat.textContent);
        if (currentUnread > 0) {
            unreadStat.textContent = Math.max(0, currentUnread - 1);
        }
    }
}

// Modal de promoção
function openPromotionModal() {
    document.getElementById('promotionModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePromotionModal() {
    document.getElementById('promotionModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('promotionForm').reset();
}

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
    btnText.textContent = '<?php echo __("notifications.loading"); ?>';
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
            alert(`<?php echo __("notifications.notification_sent"); ?> (${data.notifications_sent} usuários)`);
            closePromotionModal();
        } else {
            alert('<?php echo __("notifications.notification_error"); ?>: ' + (data.error || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('<?php echo __("notifications.notification_error"); ?>');
    })
    .finally(() => {
        // Esconder loading
        loadingIcon.style.display = 'none';
        btnText.textContent = '<?php echo __("notifications.send_promotion"); ?>';
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

// Formatação de data em JavaScript
function formatDate(dateString) {
    const now = new Date();
    const notificationDate = new Date(dateString);
    const diffTime = Math.abs(now - notificationDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
        if (diffHours === 0) {
            const diffMinutes = Math.floor(diffTime / (1000 * 60));
            return diffMinutes + ' min atrás';
        }
        return diffHours + 'h atrás';
    } else if (diffDays === 1) {
        return 'Ontem';
    } else if (diffDays < 7) {
        return diffDays + ' dias atrás';
    } else {
        return notificationDate.toLocaleDateString('pt-BR');
    }
}

// Auto-refresh a cada 2 minutos
setInterval(function() {
    updateNotificationCount();
}, 120000);
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>