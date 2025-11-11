<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="dashboard-error">
    <div class="error-container">
        <div class="error-icon">🚫</div>
        <h1><?php echo $page_title; ?></h1>
        <p><?php echo $error_message; ?></p>
        
        <div class="error-actions">
            <a href="index.php" class="btn btn-primary"><?php echo __('nav.home'); ?></a>
            <a href="configuracoes.php" class="btn btn-secondary"><?php echo __('dashboard.become_seller'); ?></a>
        </div>
    </div>
</main>

<style>
.dashboard-error {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.error-container {
    text-align: center;
    max-width: 500px;
}

.error-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.error-container h1 {
    color: #5e2b2b;
    margin-bottom: 15px;
}

.error-container p {
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #5e2b2b;
    color: white;
}

.btn-primary:hover {
    background: #4a2222;
}

.btn-secondary {
    background: #f8f9fa;
    color: #5e2b2b;
    border: 2px solid #5e2b2b;
}

.btn-secondary:hover {
    background: #5e2b2b;
    color: white;
}

@media (max-width: 768px) {
    .error-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>