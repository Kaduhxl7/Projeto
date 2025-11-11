<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Idioma</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test { padding: 10px; margin: 10px 0; border: 1px solid #ccc; }
        .lang-links a { margin: 10px; padding: 5px 10px; background: #5e2b2b; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Teste de Idioma</h1>
    
    <div class="test">
        <strong>Idioma atual:</strong> <?php echo getCurrentLang(); ?>
    </div>
    
    <div class="test">
        <strong>Título:</strong> <?php echo __('site.title'); ?>
    </div>
    
    <div class="test">
        <strong>Home:</strong> <?php echo __('nav.home'); ?>
    </div>
    
    <div class="lang-links">
        <a href="change-language.php?lang=pt">🇧🇷 PT</a>
        <a href="change-language.php?lang=en">🇺🇸 EN</a>
        <a href="change-language.php?lang=es">🇪🇸 ES</a>
        <a href="change-language.php?lang=fr">🇫🇷 FR</a>
    </div>
    
    <div class="test">
        <a href="index.php?lang=<?php echo getCurrentLang(); ?>">← Voltar ao site</a>
    </div>
    
    <div class="test">
        <strong>Sessão:</strong> <?php echo $_SESSION['language'] ?? 'não definido'; ?><br>
        <strong>Cookie:</strong> <?php echo $_COOKIE['language'] ?? 'não definido'; ?>
    </div>
</body>
</html>