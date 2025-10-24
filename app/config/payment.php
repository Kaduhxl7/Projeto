<?php
/**
 * Configurações do Sistema de Pagamento
 * DressCode - Pagamento de Taxa de Anúncio
 */

return [
    // Valor da taxa de anúncio
    'taxa_anuncio' => 9.90,
    
    // Mercado Pago (Recomendado para Brasil)
    'mercadopago' => [
        'access_token' => 'TEST-1234567890-123456-abcdef123456789-12345678', // Token de teste
        'public_key' => 'TEST-12345678-1234-1234-1234-123456789012',
        'sandbox' => true, // true para testes, false para produção
        'webhook_url' => '/webhook-mercadopago.php'
    ],
    
    // URLs de retorno
    'urls' => [
        'success' => '/pagamento-sucesso.php',
        'failure' => '/pagamento-erro.php',
        'pending' => '/pagamento-pendente.php'
    ],
    
    // Métodos de pagamento disponíveis
    'metodos_disponiveis' => [
        'pix' => true,
        'cartao' => true,
        'boleto' => false // Desabilitado por simplicidade
    ],
    
    // Configurações gerais
    'moeda' => 'BRL',
    'pais' => 'BR',
    'timeout' => 300, // 5 minutos para completar pagamento
    
    // Notificações
    'enviar_email' => true,
    'email_admin' => 'admin@dresscode.com'
];
?>