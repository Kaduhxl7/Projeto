<?php
if (!isset($lang)) {
    require_once __DIR__ . '/../app/config/Language.php';
    $lang = Language::getInstance();
}
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : __('site.description'); ?>">
    <meta name="keywords" content="<?php echo __('site.keywords'); ?>">
    <meta name="author" content="DressCode Team">
    <title><?php echo isset($page_title) ? $page_title : __('site.title'); ?></title>
    
    <!-- Preconnect para melhor performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Martel+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"/>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/page-transitions.css">
    <link rel="stylesheet" href="user-styles.php">
    <?php
    if(isset($_SESSION['user_id'])) {
        try {
            require_once __DIR__ . '/../app/controllers/ConfiguracoesController.php';
            $configController = new ConfiguracoesController();
            $config = $configController->getConfiguracoes($_SESSION['user_id']);
            if($config['tema'] == 'escuro') {
                echo '<link rel="stylesheet" href="force-dark-theme.css">';
            }
        } catch (Exception $e) {}
    }
    ?>
    
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/Logo.png">
    
    <script>
    // Aplicar tema escuro imediatamente se necessário
    <?php
    if(isset($_SESSION['user_id'])) {
        try {
            require_once __DIR__ . '/../app/controllers/ConfiguracoesController.php';
            $configController = new ConfiguracoesController();
            $config = $configController->getConfiguracoes($_SESSION['user_id']);
            if($config['tema'] == 'escuro') {
                echo 'document.documentElement.classList.add("tema-escuro");';
                echo 'document.body.classList.add("tema-escuro");';
            }
            if($config['tamanho_fonte']) {
                echo 'document.body.classList.add("fonte-' . $config['tamanho_fonte'] . '");';
            }
        } catch (Exception $e) {}
    }
    ?>
    </script>
    
    <style>
    .notification-bell {
        position: relative;
        margin-right: 15px;
    }
    
    .bell-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8f9fa;
        text-decoration: none;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .bell-link:hover {
        background: #e9ecef;
        transform: scale(1.1);
    }
    
    .notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    @media (max-width: 768px) {
        .notification-bell {
            margin-right: 10px;
        }
        
        .bell-link {
            width: 35px;
            height: 35px;
        }
    }
    </style>
    
    <?php if(isset($_SESSION['user_id'])): ?>
    <script>
    // Atualizar contador de notificações
    function updateNotificationCount() {
        fetch('notifications.php?action=get_notifications')
            .then(response => response.json())
            .then(data => {
                const counter = document.getElementById('notificationCount');
                if (counter && data.unread_count > 0) {
                    counter.textContent = data.unread_count;
                    counter.style.display = 'flex';
                } else if (counter) {
                    counter.style.display = 'none';
                }
            })
            .catch(error => console.error('Erro ao buscar notificações:', error));
    }
    
    // Atualizar a cada 60 segundos
    document.addEventListener('DOMContentLoaded', function() {
        updateNotificationCount();
        setInterval(updateNotificationCount, 60000);
    });
    </script>
    <?php endif; ?>
    
    <?php
    if(isset($_SESSION['user_id'])) {
        try {
            require_once __DIR__ . '/../app/controllers/ConfiguracoesController.php';
            $configController = new ConfiguracoesController();
            $config = $configController->getConfiguracoes($_SESSION['user_id']);
            if($config['tema'] == 'escuro') {
                echo '<style>';
                echo 'body, body * { background-color: #1a1a1a !important; color: #fff !important; }';
                echo '.filters-sidebar, .produto-card, .card, header, footer, input, select { background-color: #2d2d2d !important; }';
                echo '</style>';
            }
        } catch (Exception $e) {}
    }
    ?>
</head>
<body <?php echo (isset($_SESSION['user_id']) && isset($config) && $config['tema'] == 'escuro') ? 'class="tema-escuro"' : ''; ?>>
    <?php require_once __DIR__ . '/transition-brand-overlay.php'; ?>
    <header role="banner">
        <div class="logo">
            <a href="index.php" aria-label="DressCode - Página inicial">
                <img src="assets/images/Logo.png" alt="DressCode - Moda Sustentável" width="120" height="auto">
            </a>
        </div>
        <nav role="navigation" aria-label="Menu principal">
            <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.home'); ?></a>
            <a href="categoria.php?cat=feminino" <?php echo ($_GET['cat'] ?? '') == 'feminino' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.women'); ?></a>
            <a href="categoria.php?cat=masculino" <?php echo ($_GET['cat'] ?? '') == 'masculino' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.men'); ?></a>
            <a href="categoria.php?cat=infantil" <?php echo ($_GET['cat'] ?? '') == 'infantil' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.kids'); ?></a>
            <a href="categoria.php?cat=acessorios" <?php echo ($_GET['cat'] ?? '') == 'acessorios' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.accessories'); ?></a>
            <a href="buscar.php" <?php echo basename($_SERVER['PHP_SELF']) == 'buscar.php' ? 'aria-current="page"' : ''; ?>>🏪 <?php echo __('nav.search_stores'); ?></a>
            <a href="faq.php" <?php echo basename($_SERVER['PHP_SELF']) == 'faq.php' ? 'aria-current="page"' : ''; ?>>❓ FAQ</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php
                // Verificar se usuário é vendedor
                try {
                    require_once __DIR__ . '/../app/config/database.php';
                    $db_check = new Database();
                    $conn_check = $db_check->getConnection();
                    $stmt_check = $conn_check->prepare("SELECT quero_vender FROM usuarios WHERE id = ?");
                    $stmt_check->execute([$_SESSION['user_id']]);
                    $is_seller = $stmt_check->fetchColumn();
                    if ($is_seller): ?>
                        <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'aria-current="page"' : ''; ?>>📊 Dashboard</a>
                    <?php endif;
                } catch (Exception $e) {} ?>
                <a href="favoritos.php" <?php echo basename($_SERVER['PHP_SELF']) == 'favoritos.php' ? 'aria-current="page"' : ''; ?>>❤️ <?php echo __('nav.favorites'); ?></a>
                <a href="configuracoes.php" <?php echo basename($_SERVER['PHP_SELF']) == 'configuracoes.php' ? 'aria-current="page"' : ''; ?>>⚙️ <?php echo __('nav.settings'); ?></a>
                <a href="privacidade.php" <?php echo basename($_SERVER['PHP_SELF']) == 'privacidade.php' ? 'aria-current="page"' : ''; ?>>🔒 <?php echo __('nav.privacy'); ?></a>
                <a href="logout.php" aria-label="<?php echo __('nav.logout'); ?>"><?php echo __('nav.logout'); ?></a>
            <?php else: ?>
                <a href="login.php"><?php echo __('nav.login'); ?></a>
            <?php endif; ?>
        </nav>
        
        <div class="header-actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="notification-bell">
                    <a href="notifications.php" class="bell-link" title="<?php echo __('notifications.bell_title'); ?>">
                        🔔
                        <span class="notification-count" id="notificationCount" style="display: none;">0</span>
                    </a>
                </div>
            <?php endif; ?>
            <div class="language-selector">
                <select onchange="window.location.href='change-language.php?lang='+this.value" style="padding: 8px; border-radius: 6px; border: 1px solid #5e2b2b; background: white; color: #5e2b2b; font-weight: 500;">
                    <option value="pt" <?php echo (!isset($_GET['lang']) || $_GET['lang'] == 'pt') ? 'selected' : ''; ?>>🇧🇷 PT</option>
                    <option value="en" <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'en') ? 'selected' : ''; ?>>🇺🇸 EN</option>
                    <option value="es" <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'es') ? 'selected' : ''; ?>>🇪🇸 ES</option>
                    <option value="fr" <?php echo (isset($_GET['lang']) && $_GET['lang'] == 'fr') ? 'selected' : ''; ?>>🇫🇷 FR</option>
                </select>
            </div>
        </div>
        <div class="search-box" role="search">
            <form action="busca.php" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="search" placeholder="<?php echo __('nav.search_placeholder'); ?>" aria-label="Campo de busca" id="searchInput">
                <button type="submit" class="search-icon" aria-label="Buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#5e2b2b" viewBox="0 0 24 24">
                        <path d="M10 2a8 8 0 0 1 6.32 12.906l5.387 5.387-1.414 1.414-5.387-5.387A8 8 0 1 1 10 2zm0 2a6 6 0 1 0 0 12a6 6 0 0 0 0-12z"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>