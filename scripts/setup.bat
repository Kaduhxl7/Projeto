@echo off
chcp 65001 >nul
cls

echo ========================================
echo    🚀 DressCode - Setup Automatizado
echo ========================================
echo.

:: Verificar se PHP está instalado
echo [1/6] Verificando PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP não encontrado! Instale o PHP ou use o Laragon.
    pause
    exit /b 1
)
echo ✅ PHP encontrado

:: Verificar se MySQL está disponível
echo [2/6] Verificando MySQL...
mysql --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  MySQL CLI não encontrado, mas continuando...
) else (
    echo ✅ MySQL encontrado
)

:: Criar arquivo .env se não existir
echo [3/6] Configurando ambiente...
if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo ✅ Arquivo .env criado
) else (
    echo ✅ Arquivo .env já existe
)

:: Verificar se Composer existe e instalar dependências
echo [4/6] Verificando dependências...
if exist "composer.json" (
    composer install --no-dev --optimize-autoloader >nul 2>&1
    if %errorlevel% equ 0 (
        echo ✅ Dependências instaladas
    ) else (
        echo ⚠️  Erro ao instalar dependências, mas continuando...
    )
) else (
    echo ✅ Nenhuma dependência do Composer necessária
)

:: Configurar banco de dados
echo [5/6] Configurando banco de dados...
php -r "
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo '✅ Banco de dados criado/verificado' . PHP_EOL;
    
    $pdo = new PDO('mysql:host=localhost;dbname=dresscode;charset=utf8mb4', 'root', '');
    $sql = file_get_contents('database/database.sql');
    if ($sql) {
        $statements = explode(';', $sql);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^(CREATE DATABASE|USE)/i', $statement)) {
                try {
                    $pdo->exec($statement);
                } catch (Exception $e) {
                    // Ignora erros de tabelas já existentes
                }
            }
        }
        echo '✅ Tabelas configuradas' . PHP_EOL;
    }
} catch (Exception $e) {
    echo '⚠️  Erro no banco: ' . $e->getMessage() . PHP_EOL;
    echo '   Verifique se o MySQL está rodando' . PHP_EOL;
}
"

:: Iniciar servidor
echo [6/6] Iniciando servidor...
echo.
echo ========================================
echo ✅ Projeto iniciado com sucesso!
echo 🌐 Acesse: http://localhost:8000
echo.
echo 🔐 Login de teste:
echo    Email: teste@dresscode.com
echo    Senha: 123456
echo.
echo ⚠️  Para parar o servidor, feche esta janela
echo ========================================
echo.

cd public
php -S localhost:8000