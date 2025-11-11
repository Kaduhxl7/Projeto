<?php

// Sistema de idiomas simplificado
session_start();

// Definir idioma
if (isset($_GET['lang']) && in_array($_GET['lang'], ['pt', 'en', 'es', 'fr'])) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_GET['lang'], time() + (365 * 24 * 60 * 60), '/');
}

// Obter idioma atual
$current_lang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $current_lang;

// Carregar traduções
$translations = [];
$lang_file = __DIR__ . "/../languages/{$current_lang}.php";
if (file_exists($lang_file)) {
    $translations = include $lang_file;
}

// Função de tradução
if (!function_exists('__')) {
    function __($key, $params = []) {
        global $translations;
        $keys = explode('.', $key);
        $value = $translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key;
            }
        }
        
        if (!empty($params) && is_string($value)) {
            foreach ($params as $param => $replacement) {
                $value = str_replace("{{$param}}", $replacement, $value);
            }
        }
        
        return $value;
    }
}

// Função para obter idioma atual
if (!function_exists('getCurrentLang')) {
    function getCurrentLang() {
        global $current_lang;
        return $current_lang;
    }
}

// Função para URLs com idioma
if (!function_exists('url_with_lang')) {
    function url_with_lang($url) {
        global $current_lang;
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'lang=' . $current_lang;
    }
}