<?php
// Configurações gerais do projeto
define('PROJECT_NAME', 'DressCode');
define('PROJECT_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/Projeto-main/public/');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 em HTTPS

// Configurações de erro (desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de upload (futuro)
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', '../public/assets/uploads/');

// Configurações de email (futuro)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// Configurações de paginação
define('ITEMS_PER_PAGE', 12);

// Status de produtos
define('PRODUCT_STATUS_ACTIVE', 1);
define('PRODUCT_STATUS_INACTIVE', 0);
define('PRODUCT_STATUS_SOLD', 2);
?>