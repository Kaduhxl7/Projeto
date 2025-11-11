<?php

class TranslationHelper {
    
    public static function translateDynamic($text, $context = 'products') {
        return $text; // Retorna texto original por enquanto
    }
    
    public static function translateColor($color) {
        if (empty($color)) return $color;
        
        $key = 'colors.' . strtolower($color);
        $translation = __($key);
        
        return ($translation === $key) ? $color : $translation;
    }
    
    public static function translateSize($size) {
        if (empty($size)) return $size;
        
        $key = 'sizes.' . strtolower($size);
        $translation = __($key);
        
        return ($translation === $key) ? $size : $translation;
    }
    
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
    
    public static function ensureTranslated($text) {
        return $text;
    }
}