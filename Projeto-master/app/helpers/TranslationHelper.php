<?php

require_once __DIR__ . '/TranslationAuditor.php';

class TranslationHelper {
    
    /**
     * Traduz texto dinâmico do banco de dados se existir tradução
     */
    public static function translateDynamic($text, $context = 'products') {
        if (empty($text)) return $text;
        
        // Para produtos, usar ProductTranslator
        if ($context === 'products' || $context === 'product_name') {
            return ProductTranslator::translateName($text);
        }
        
        if ($context === 'product_description') {
            return ProductTranslator::translateDescription($text);
        }
        
        // Primeiro tenta tradução automática
        $autoTranslated = TranslationAuditor::autoTranslate($text);
        if ($autoTranslated !== $text) {
            return $autoTranslated;
        }
        
        $key = $context . '.' . self::createSlug($text);
        $translation = __($key);
        
        return ($translation === $key) ? $text : $translation;
    }
    
    /**
     * Traduz cores do banco de dados
     */
    public static function translateColor($color) {
        if (empty($color)) return $color;
        
        $key = 'colors.' . strtolower($color);
        $translation = __($key);
        
        return ($translation === $key) ? $color : $translation;
    }
    
    /**
     * Traduz tamanhos do banco de dados
     */
    public static function translateSize($size) {
        if (empty($size)) return $size;
        
        $key = 'sizes.' . strtolower($size);
        $translation = __($key);
        
        return ($translation === $key) ? $size : $translation;
    }
    
    /**
     * Cria slug para chave de tradução
     */
    private static function createSlug($text) {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '_', $slug);
        return trim($slug, '_');
    }
    
    /**
     * Formata preço por idioma
     */
    public static function formatPrice($price) {
        $lang = getCurrentLang();
        
        switch ($lang) {
            case 'en':
                return '$' . number_format($price, 2, '.', ',');
            case 'fr':
                return number_format($price, 2, ',', ' ') . ' €';
            case 'es':
                return number_format($price, 2, ',', '.') . ' €';
            default:
                return 'R$ ' . number_format($price, 2, ',', '.');
        }
    }
    
    /**
     * Processa texto para garantir que seja traduzido
     */
    public static function ensureTranslated($text) {
        if (empty($text)) return $text;
        
        // Verifica se é suspeito de estar em português
        if (TranslationAuditor::auditText($text)) {
            return TranslationAuditor::autoTranslate($text);
        }
        
        return $text;
    }
}