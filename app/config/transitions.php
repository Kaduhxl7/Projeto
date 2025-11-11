<?php
/**
 * Configurações do Sistema de Transições
 * DressCode - Transições de Página
 */

return [
    // Nome da marca exibido durante a transição
    'brand_name' => 'DressCode',
    
    // Duração total da transição (em milissegundos)
    'transition_duration' => 800,
    
    // Cores do gradiente de fundo
    'background_gradient' => [
        'start' => '#5e2b2b',
        'end' => '#8b4444'
    ],
    
    // Cores para tema escuro
    'dark_theme_gradient' => [
        'start' => '#1a1a1a',
        'end' => '#2d2d2d'
    ],
    
    // Links que devem ser ignorados pelo sistema de transições
    'skip_links' => [
        'logout.php',
        'toggle-favorito.php',
        'change-language.php',
        'get-avaliacoes.php',
        'salvar-configuracoes.php'
    ],
    
    // Configurações de animação
    'animation' => [
        'fade_in_duration' => '0.8s',
        'fade_out_duration' => '0.4s',
        'scale_start' => 0.8,
        'scale_peak' => 1.1,
        'scale_end' => 1.0
    ]
];
?>