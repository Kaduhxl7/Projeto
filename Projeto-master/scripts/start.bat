@echo off
chcp 65001 >nul
cls

echo ========================================
echo    ⚡ DressCode - Início Rápido
echo ========================================
echo.

:: Verificar se .env existe
if not exist ".env" (
    echo ⚠️  Arquivo .env não encontrado!
    echo 🔧 Execute 'setup.bat' primeiro para configuração inicial
    echo.
    pause
    exit /b 1
)

echo 🚀 Iniciando servidor...
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