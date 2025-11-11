<?php
require_once __DIR__ . '/../app/config/bootstrap.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Final</title>
    <style>body{font-family:Arial;margin:20px;} .box{padding:10px;margin:10px 0;border:1px solid #ccc;} a{margin:10px;padding:5px 10px;background:#5e2b2b;color:white;text-decoration:none;}</style>
</head>
<body>
    <h1>Teste Final do Sistema de Idiomas</h1>
    
    <div class="box">
        <strong>Idioma:</strong> <?php echo getCurrentLang(); ?><br>
        <strong>Título:</strong> <?php echo __('site.title'); ?><br>
        <strong>Home:</strong> <?php echo __('nav.home'); ?>
    </div>
    
    <div class="box">
        <a href="change-language-simple.php?lang=pt">🇧🇷 PT</a>
        <a href="change-language-simple.php?lang=en">🇺🇸 EN</a>
        <a href="change-language-simple.php?lang=es">🇪🇸 ES</a>
        <a href="change-language-simple.php?lang=fr">🇫🇷 FR</a>
    </div>
    
    <div class="box">
        <a href="index.php">← Voltar ao site</a>
    </div>
</body>
</html>