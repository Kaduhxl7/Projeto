<?php

class LanguageMiddleware {
    
    public static function handle() {
        // Garantir que a sessão está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar se há parâmetro de idioma na URL
        if (isset($_GET['lang'])) {
            $lang = Language::getInstance();
            if ($lang->setLanguage($_GET['lang'])) {
                // Idioma definido com sucesso
                $_SESSION['language_set'] = true;
            }
        }
        
        // Garantir que o idioma está sempre definido
        $currentLang = Language::getInstance()->getCurrentLanguage();
        if (!isset($_SESSION['language']) || $_SESSION['language'] !== $currentLang) {
            $_SESSION['language'] = $currentLang;
            setcookie('language', $currentLang, time() + (365 * 24 * 60 * 60), '/', '', false, false);
        }
        
        // Adicionar idioma aos links se necessário
        self::addLanguageToLinks();
    }
    
    private static function addLanguageToLinks() {
        // Capturar output e adicionar parâmetro de idioma aos links internos
        if (!isset($_SESSION['language_middleware_active'])) {
            $_SESSION['language_middleware_active'] = true;
            ob_start([self::class, 'processOutput']);
        }
    }
    
    public static function processOutput($buffer) {
        $currentLang = Language::getInstance()->getCurrentLanguage();
        
        // Adicionar parâmetro de idioma aos links internos que não têm
        $pattern = '/href=["\']([^"\']*\.php)([^"\']*)["\'](?![^>]*lang=)/i';
        $replacement = function($matches) use ($currentLang) {
            $url = $matches[1] . $matches[2];
            $separator = strpos($url, '?') !== false ? '&' : '?';
            return 'href="' . $url . $separator . 'lang=' . $currentLang . '"';
        };
        
        return preg_replace_callback($pattern, $replacement, $buffer);
    }
    
    public static function getCurrentLanguageParam() {
        $currentLang = Language::getInstance()->getCurrentLanguage();
        return 'lang=' . $currentLang;
    }
    
    public static function addLanguageToUrl($url) {
        $currentLang = Language::getInstance()->getCurrentLanguage();
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'lang=' . $currentLang;
    }
}