<?php
// Garantir que o sistema de idiomas está inicializado
if (!isset($lang)) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../config/Language.php';
    $lang = Language::getInstance();
}
?>
<header role="banner">
    <div class="logo">
        <a href="index.php" aria-label="<?php echo __('site.title'); ?>">
            <img src="assets/images/Logo.png" alt="<?php echo __('site.title'); ?>" width="120" height="auto">
        </a>
    </div>
    <nav role="navigation" aria-label="<?php echo __('nav.home'); ?>">
        <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.home'); ?></a>
        <a href="categoria.php?cat=feminino" <?php echo ($_GET['cat'] ?? '') == 'feminino' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.women'); ?></a>
        <a href="categoria.php?cat=masculino" <?php echo ($_GET['cat'] ?? '') == 'masculino' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.men'); ?></a>
        <a href="categoria.php?cat=infantil" <?php echo ($_GET['cat'] ?? '') == 'infantil' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.kids'); ?></a>
        <a href="categoria.php?cat=acessorios" <?php echo ($_GET['cat'] ?? '') == 'acessorios' ? 'aria-current="page"' : ''; ?>><?php echo __('nav.accessories'); ?></a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php" aria-label="<?php echo __('nav.logout'); ?>"><?php echo __('nav.logout'); ?></a>
        <?php else: ?>
            <a href="login.php"><?php echo __('nav.login'); ?></a>
        <?php endif; ?>
    </nav>
    
    <div class="header-actions">
        <div class="language-selector">
            <select onchange="changeLanguage(this.value)" style="padding: 8px; border-radius: 6px; border: 1px solid #5e2b2b; background: white; color: #5e2b2b; font-weight: 500;">
                <option value="pt" <?php echo getCurrentLang() == 'pt' ? 'selected' : ''; ?>>🇧🇷 PT</option>
                <option value="en" <?php echo getCurrentLang() == 'en' ? 'selected' : ''; ?>>🇺🇸 EN</option>
                <option value="es" <?php echo getCurrentLang() == 'es' ? 'selected' : ''; ?>>🇪🇸 ES</option>
                <option value="fr" <?php echo getCurrentLang() == 'fr' ? 'selected' : ''; ?>>🇫🇷 FR</option>
            </select>
        </div>
    </div>
    
    <div class="search-box" role="search">
        <form action="busca.php" method="GET" style="display: flex; width: 100%;">
            <input type="text" name="search" placeholder="<?php echo __('nav.search_placeholder'); ?>" aria-label="<?php echo __('nav.search_placeholder'); ?>" 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" id="searchInput">
            <button type="submit" class="search-icon" aria-label="<?php echo __('search.title'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#5e2b2b" viewBox="0 0 24 24">
                    <path d="M10 2a8 8 0 0 1 6.32 12.906l5.387 5.387-1.414 1.414-5.387-5.387A8 8 0 1 1 10 2zm0 2a6 6 0 1 0 0 12a6 6 0 0 0 0-12z"/>
                </svg>
            </button>
        </form>
    </div>
</header>

<script>
function changeLanguage(lang) {
    window.location.href = 'change-language.php?lang=' + lang;
}
</script>