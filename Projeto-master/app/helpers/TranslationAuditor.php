<?php

class TranslationAuditor {
    
    /**
     * Identifica textos em português que precisam ser traduzidos
     */
    public static function auditText($text) {
        // Lista de palavras comuns em português que indicam texto não traduzido
        $portugueseWords = [
            'publicado', 'avaliações', 'usuário', 'feminino', 'masculino', 
            'infantil', 'acessórios', 'artesanal', 'vintage', 'seminovo',
            'usado', 'novo', 'blusa', 'vestido', 'calça', 'saia', 'camisa',
            'tênis', 'sapato', 'bolsa', 'colar', 'anel', 'brinco',
            'preto', 'branco', 'azul', 'vermelho', 'verde', 'amarelo',
            'rosa', 'roxo', 'marrom', 'cinza', 'bege', 'floral'
        ];
        
        $lowerText = strtolower($text);
        foreach ($portugueseWords as $word) {
            if (strpos($lowerText, $word) !== false) {
                return true; // Texto suspeito de estar em português
            }
        }
        return false;
    }
    
    /**
     * Traduz automaticamente textos comuns
     */
    public static function autoTranslate($text) {
        // Mapeamento de textos comuns
        $commonTranslations = [
            'Publicado em' => 'product.published_on',
            'Avaliações' => 'product.reviews',
            'Usuário Teste' => 'user.test_user',
            'Condition:' => 'product.condition',
            'Like New' => 'conditions.like_new',
            'Feminino' => 'categories.feminino',
            'Masculino' => 'categories.masculino',
            'Infantil' => 'categories.infantil',
            'Acessórios' => 'categories.acessorios'
        ];
        
        foreach ($commonTranslations as $original => $key) {
            if (stripos($text, $original) !== false) {
                return __($key);
            }
        }
        
        return $text;
    }
}