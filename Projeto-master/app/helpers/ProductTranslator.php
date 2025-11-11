<?php

class ProductTranslator {
    private static $productTranslations = null;
    
    /**
     * Carrega traduções de produtos para o idioma atual
     */
    private static function loadProductTranslations() {
        if (self::$productTranslations !== null) {
            return;
        }
        
        $currentLang = getCurrentLang();
        $file = __DIR__ . "/../languages/products_{$currentLang}.php";
        
        if (file_exists($file)) {
            self::$productTranslations = include $file;
        } else {
            // Fallback para português
            $fallbackFile = __DIR__ . "/../languages/products_pt.php";
            self::$productTranslations = file_exists($fallbackFile) ? include $fallbackFile : [];
        }
    }
    
    /**
     * Traduz nome do produto
     */
    public static function translateName($name) {
        if (empty($name)) return $name;
        
        self::loadProductTranslations();
        $key = self::createKey($name);
        
        return self::$productTranslations[$key] ?? $name;
    }
    
    /**
     * Traduz descrição do produto
     */
    public static function translateDescription($description) {
        if (empty($description)) return $description;
        
        self::loadProductTranslations();
        $key = self::createKey($description);
        
        return self::$productTranslations[$key] ?? $description;
    }
    
    /**
     * Cria chave a partir do texto
     */
    private static function createKey($text) {
        $key = strtolower($text);
        $key = preg_replace('/[^a-z0-9\s]/', '', $key);
        $key = preg_replace('/\s+/', '_', $key);
        return trim($key, '_');
    }
    
    /**
     * Força recarregamento das traduções (útil ao trocar idioma)
     */
    public static function reload() {
        self::$productTranslations = null;
    }
}