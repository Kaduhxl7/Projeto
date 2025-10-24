@echo off
echo ========================================
echo    DressCode - Instalacao Automatica
echo ========================================
echo.

echo Verificando PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERRO: PHP nao encontrado!
    echo Instale o Laragon ou XAMPP primeiro.
    pause
    exit /b 1
)

echo PHP encontrado! Configurando projeto...
echo.

echo Executando configuracao automatica...
php utils/configurar.php

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo         INSTALACAO CONCLUIDA!
    echo ========================================
    echo.
    echo Deseja iniciar o servidor agora? (S/N)
    set /p resposta=
    
    if /i "%resposta%"=="S" (
        echo.
        echo Iniciando servidor...
        echo Acesse: http://localhost:8000
        echo.
        cd public
        php -S localhost:8000
    ) else (
        echo.
        echo Para iniciar depois, execute:
        echo   start-server-windows.bat
        echo.
        pause
    )
) else (
    echo.
    echo ERRO na configuracao. Verifique o MySQL.
    pause
)