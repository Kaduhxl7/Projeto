<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'DressCode - Plataforma de brechós online. Moda sustentável e consciente.'; ?>">
    <meta name="keywords" content="brechó, moda sustentável, roupas usadas, moda consciente, vintage">
    <meta name="author" content="DressCode Team">
    <title><?php echo isset($page_title) ? $page_title : 'DressCode - Moda Sustentável'; ?></title>
    
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
    
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/Logo.png">
</head>
<body>
    <header role="banner">
        <div class="logo">
            <a href="index.php" aria-label="DressCode - Página inicial">
                <img src="assets/images/Logo.png" alt="DressCode - Moda Sustentável" width="120" height="auto">
            </a>
        </div>
        <nav role="navigation" aria-label="Menu principal">
            <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'aria-current="page"' : ''; ?>>Início</a>
            <a href="categoria.php?cat=feminino" <?php echo ($_GET['cat'] ?? '') == 'feminino' ? 'aria-current="page"' : ''; ?>>Feminino</a>
            <a href="categoria.php?cat=masculino" <?php echo ($_GET['cat'] ?? '') == 'masculino' ? 'aria-current="page"' : ''; ?>>Masculino</a>
            <a href="categoria.php?cat=infantil" <?php echo ($_GET['cat'] ?? '') == 'infantil' ? 'aria-current="page"' : ''; ?>>Infantil</a>
            <a href="categoria.php?cat=acessorios" <?php echo ($_GET['cat'] ?? '') == 'acessorios' ? 'aria-current="page"' : ''; ?>>Acessórios</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" aria-label="Sair da conta">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar/Cadastrar</a>
            <?php endif; ?>
        </nav>
        <div class="search-box" role="search">
            <form action="busca.php" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="search" placeholder="Buscar produtos..." aria-label="Campo de busca" id="searchInput">
                <button type="submit" class="search-icon" aria-label="Buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#5e2b2b" viewBox="0 0 24 24">
                        <path d="M10 2a8 8 0 0 1 6.32 12.906l5.387 5.387-1.414 1.414-5.387-5.387A8 8 0 1 1 10 2zm0 2a6 6 0 1 0 0 12a6 6 0 0 0 0-12z"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>