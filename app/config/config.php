<?php
// Carregar variáveis de ambiente
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Carregar .env
loadEnv(__DIR__ . '/../../.env');

// Função helper para pegar variáveis de ambiente
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Configurações gerais do projeto
define('PROJECT_NAME', env('APP_NAME', 'DressCode'));
define('PROJECT_VERSION', env('APP_VERSION', '1.0.0'));
define('BASE_URL', 'http://' . env('SERVER_HOST', 'localhost') . ':' . env('SERVER_PORT', '8000') . '/');

// Configurações de sessão
ini_set('session.cookie_httponly', env('SESSION_HTTPONLY', 1));
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', env('SESSION_SECURE', 0));

// Configurações de erro (desenvolvimento)
if (env('APP_ENV', 'development') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set(env('TIMEZONE', 'America/Sao_Paulo'));

// Configurações de upload
define('MAX_FILE_SIZE', env('MAX_FILE_SIZE', 5242880));
define('UPLOAD_PATH', env('UPLOAD_PATH', '../public/assets/uploads/'));

// Configurações de email
define('SMTP_HOST', env('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', env('SMTP_PORT', 587));
define('SMTP_USERNAME', env('SMTP_USERNAME', ''));
define('SMTP_PASSWORD', env('SMTP_PASSWORD', ''));

// Configurações de paginação
define('ITEMS_PER_PAGE', 12);

// Status de produtos
define('PRODUCT_STATUS_ACTIVE', 1);
define('PRODUCT_STATUS_INACTIVE', 0);
define('PRODUCT_STATUS_SOLD', 2);
?>