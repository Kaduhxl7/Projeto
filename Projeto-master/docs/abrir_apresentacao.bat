@echo off
echo ========================================
echo   ABRINDO APRESENTACAO DRESSCODE
echo ========================================
echo.
echo Abrindo os 3 arquivos HTML no navegador...
echo.

start "" "APRESENTACAO_DRESSCODE.html"
timeout /t 2 /nobreak >nul

start "" "APRESENTACAO_DRESSCODE_PARTE2.html"
timeout /t 2 /nobreak >nul

start "" "APRESENTACAO_DRESSCODE_PARTE3.html"

echo.
echo ========================================
echo   ARQUIVOS ABERTOS!
echo ========================================
echo.
echo Para converter para PDF:
echo 1. Pressione Ctrl+P em cada aba
echo 2. Selecione "Salvar como PDF"
echo 3. Salve com os nomes sugeridos
echo.
echo Consulte GUIA_CONVERSAO_PDF.md para mais detalhes
echo.
pause
