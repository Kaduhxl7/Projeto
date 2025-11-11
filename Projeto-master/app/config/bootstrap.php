<?php
/**
 * Bootstrap do sistema - Inicialização global
 * Este arquivo deve ser incluído em todas as páginas
 */

// Configurar sessão antes de iniciar
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_lifetime', 0); // Sessão expira quando o navegador fecha
    session_start();
}

// Carregar conexão com banco de dados
require_once __DIR__ . '/database.php';
$database = new Database();
$pdo = $database->getConnection();

// Carregar sistema de idiomas
require_once __DIR__ . '/Language.php';
require_once __DIR__ . '/../helpers/TranslationHelper.php';
require_once __DIR__ . '/../helpers/ProductTranslator.php';

// Inicializar instância global do sistema de idiomas
$GLOBALS['lang'] = Language::getInstance();

// Função helper global para facilitar o uso
if (!function_exists('t')) {
    function t($key, $params = []) {
        return $GLOBALS['lang']->get($key, $params);
    }
}

// Função para obter idioma atual
if (!function_exists('current_lang')) {
    function current_lang() {
        return $GLOBALS['lang']->getCurrentLanguage();
    }
}

// Função para obter idiomas disponíveis
if (!function_exists('available_languages')) {
    function available_languages() {
        return $GLOBALS['lang']->getAvailableLanguages();
    }
}

// Função para verificar se usuário está logado
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}