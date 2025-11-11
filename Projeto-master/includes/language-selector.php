<?php
require_once __DIR__ . '/../app/config/Language.php';
$lang = Language::getInstance();
$currentLang = $lang->getCurrentLanguage();
$availableLanguages = $lang->getAvailableLanguages();
?>

<div class="language-selector">
    <button class="language-toggle" onclick="toggleLanguageMenu()" aria-label="<?php echo __('language.select'); ?>">
        <span class="current-flag"><?php echo $lang->getCurrentLanguageFlag(); ?></span>
        <span class="current-lang"><?php echo strtoupper($currentLang); ?></span>
        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
            <path d="M6 8L2 4h8L6 8z"/>
        </svg>
    </button>
    
    <div class="language-menu" id="languageMenu">
        <?php foreach ($availableLanguages as $code => $info): ?>
            <a href="change-language.php?lang=<?php echo $code; ?>" 
               class="language-option <?php echo $code === $currentLang ? 'active' : ''; ?>"
               data-lang="<?php echo $code; ?>">
                <span class="flag"><?php echo $info['flag']; ?></span>
                <span class="name"><?php echo $info['name']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.language-selector {
    position: relative;
    display: inline-block;
}

.language-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
}

.language-toggle:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
}

.current-flag {
    font-size: 16px;
}

.current-lang {
    font-weight: 500;
    min-width: 20px;
}

.dropdown-arrow {
    transition: transform 0.2s ease;
}

.language-selector.open .dropdown-arrow {
    transform: rotate(180deg);
}

.language-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 4px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 160px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
}

.language-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.language-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    color: #333;
    text-decoration: none;
    transition: background-color 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.language-option:last-child {
    border-bottom: none;
}

.language-option:hover {
    background-color: #f8f9fa;
}

.language-option.active {
    background-color: #e3f2fd;
    color: #1976d2;
    font-weight: 500;
}

.language-option .flag {
    font-size: 18px;
}

.language-option .name {
    font-size: 14px;
}

@media (max-width: 768px) {
    .language-toggle {
        padding: 6px 10px;
        font-size: 13px;
    }
    
    .current-flag {
        font-size: 14px;
    }
    
    .language-menu {
        min-width: 140px;
    }
    
    .language-option {
        padding: 8px 12px;
    }
}
</style>

<script>
function toggleLanguageMenu() {
    const selector = document.querySelector('.language-selector');
    const menu = document.getElementById('languageMenu');
    
    selector.classList.toggle('open');
    menu.classList.toggle('show');
}

// Fechar menu ao clicar fora
document.addEventListener('click', function(event) {
    const selector = document.querySelector('.language-selector');
    if (!selector.contains(event.target)) {
        selector.classList.remove('open');
        document.getElementById('languageMenu').classList.remove('show');
    }
});

// Fechar menu ao pressionar ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelector('.language-selector').classList.remove('open');
        document.getElementById('languageMenu').classList.remove('show');
    }
});
</script>