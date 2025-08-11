@echo off
echo ========================================
echo    DressCode - Iniciando Servidor
echo ========================================
echo.
echo Verificando PHP...
php --version
echo.
echo Iniciando servidor na porta 8000...
echo Acesse: http://localhost:8000
echo.
echo Pressione Ctrl+C para parar o servidor
echo ========================================
cd public
php -S localhost:8000
pause