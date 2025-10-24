<?php
echo "🔍 Detectando porta do projeto...\n\n";

// Portas comuns do Laragon/Apache
$portas = [80, 8080, 443, 8000, 3000, 8888, 9000];

foreach ($portas as $porta) {
    $url = "http://localhost:$porta/Projeto/Projeto-master/public/index.php";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 3,
            'method' => 'GET'
        ]
    ]);
    
    $result = @file_get_contents($url, false, $context);
    
    if ($result !== false) {
        echo "✅ ENCONTRADO! Seu projeto está rodando na porta: $porta\n";
        echo "🔗 URL: http://localhost:$porta/Projeto/Projeto-master/public/\n";
        echo "🧪 Teste reports: http://localhost:$porta/Projeto/Projeto-master/public/test-report.html\n";
        exit;
    } else {
        echo "❌ Porta $porta - não encontrado\n";
    }
}

echo "\n⚠️  Projeto não encontrado nas portas testadas.\n";
echo "💡 Verifique se o Laragon está rodando ou teste manualmente:\n";
echo "   - http://localhost/Projeto/Projeto-master/public/\n";
echo "   - http://localhost:8080/Projeto/Projeto-master/public/\n";
?>