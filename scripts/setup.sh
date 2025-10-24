#!/bin/bash

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

clear

echo -e "${BLUE}========================================"
echo -e "   🚀 DressCode - Setup Automatizado"
echo -e "========================================${NC}"
echo

# Verificar se PHP está instalado
echo -e "[1/6] Verificando PHP..."
if command -v php &> /dev/null; then
    echo -e "${GREEN}✅ PHP encontrado$(php --version | head -n1)${NC}"
else
    echo -e "${RED}❌ PHP não encontrado! Instale o PHP primeiro.${NC}"
    exit 1
fi

# Verificar se MySQL está disponível
echo -e "[2/6] Verificando MySQL..."
if command -v mysql &> /dev/null; then
    echo -e "${GREEN}✅ MySQL encontrado${NC}"
else
    echo -e "${YELLOW}⚠️  MySQL CLI não encontrado, mas continuando...${NC}"
fi

# Criar arquivo .env se não existir
echo -e "[3/6] Configurando ambiente..."
if [ ! -f ".env" ]; then
    cp ".env.example" ".env"
    echo -e "${GREEN}✅ Arquivo .env criado${NC}"
else
    echo -e "${GREEN}✅ Arquivo .env já existe${NC}"
fi

# Verificar se Composer existe e instalar dependências
echo -e "[4/6] Verificando dependências..."
if [ -f "composer.json" ]; then
    if command -v composer &> /dev/null; then
        composer install --no-dev --optimize-autoloader &> /dev/null
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✅ Dependências instaladas${NC}"
        else
            echo -e "${YELLOW}⚠️  Erro ao instalar dependências, mas continuando...${NC}"
        fi
    else
        echo -e "${YELLOW}⚠️  Composer não encontrado, mas continuando...${NC}"
    fi
else
    echo -e "${GREEN}✅ Nenhuma dependência do Composer necessária${NC}"
fi

# Configurar banco de dados
echo -e "[5/6] Configurando banco de dados..."
php -r "
try {
    \$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo '✅ Banco de dados criado/verificado' . PHP_EOL;
    
    \$pdo = new PDO('mysql:host=localhost;dbname=dresscode;charset=utf8mb4', 'root', '');
    \$sql = file_get_contents('database/database.sql');
    if (\$sql) {
        \$statements = explode(';', \$sql);
        foreach (\$statements as \$statement) {
            \$statement = trim(\$statement);
            if (!empty(\$statement) && !preg_match('/^(CREATE DATABASE|USE)/i', \$statement)) {
                try {
                    \$pdo->exec(\$statement);
                } catch (Exception \$e) {
                    // Ignora erros de tabelas já existentes
                }
            }
        }
        echo '✅ Tabelas configuradas' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo '⚠️  Erro no banco: ' . \$e->getMessage() . PHP_EOL;
    echo '   Verifique se o MySQL está rodando' . PHP_EOL;
}
"

# Dar permissões de execução se necessário
chmod +x setup.sh 2>/dev/null

# Iniciar servidor
echo -e "[6/6] Iniciando servidor..."
echo
echo -e "${BLUE}========================================"
echo -e "${GREEN}✅ Projeto iniciado com sucesso!"
echo -e "${BLUE}🌐 Acesse: http://localhost:8000"
echo
echo -e "🔐 Login de teste:"
echo -e "   Email: teste@dresscode.com"
echo -e "   Senha: 123456"
echo
echo -e "${YELLOW}⚠️  Para parar o servidor, pressione Ctrl+C"
echo -e "${BLUE}========================================${NC}"
echo

cd public
php -S localhost:8000