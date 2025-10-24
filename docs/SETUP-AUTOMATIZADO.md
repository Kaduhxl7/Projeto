# 🚀 Sistema de Inicialização Automatizado - DressCode

## Visão Geral

O DressCode agora possui um sistema de inicialização completamente automatizado e multiplataforma que substitui todos os scripts antigos de setup.

## 📋 Características

### ✅ Automação Completa
- Detecção automática de PHP e MySQL
- Criação automática do banco de dados
- Configuração automática do ambiente (.env)
- Instalação automática de dependências
- Inicialização do servidor web

### 🌍 Multiplataforma
- **Windows**: `setup.bat`
- **Linux/macOS**: `setup.sh`
- Detecção automática do sistema operacional
- Compatibilidade com Laragon, XAMPP, WAMP, MAMP

### 🔧 Configuração Inteligente
- Arquivo `.env` baseado em `.env.example`
- Variáveis de ambiente personalizáveis
- Configurações persistentes entre reinicializações
- Compatibilidade com múltiplas máquinas

## 🚀 Como Usar

### Primeira Execução (Setup Completo)

**Windows:**
```bash
setup.bat
```

**Linux/macOS:**
```bash
bash setup.sh
```

### Execuções Subsequentes (Início Rápido)

**Windows:**
```bash
start.bat
```

**Linux/macOS:**
```bash
bash setup.sh
```

## 📁 Estrutura de Arquivos

```
Projeto-master/
├── setup.bat              # Script de setup para Windows
├── setup.sh               # Script de setup para Linux/macOS
├── start.bat              # Início rápido para Windows
├── .env.example           # Template de configurações
├── .env                   # Configurações locais (criado automaticamente)
└── database/
    └── database.sql       # Script de criação do banco
```

## ⚙️ Configurações (.env)

O arquivo `.env` é criado automaticamente baseado no `.env.example` e contém:

```env
# Banco de Dados
DB_HOST=localhost
DB_NAME=dresscode
DB_USER=root
DB_PASS=

# Servidor
SERVER_HOST=localhost
SERVER_PORT=8000

# Aplicação
APP_NAME=DressCode
APP_VERSION=1.0.0
APP_ENV=development
```

## 🔍 O que o Script Faz

### [1/6] Verificação do PHP
- Verifica se PHP está instalado e acessível
- Exibe a versão do PHP encontrada
- Para a execução se PHP não for encontrado

### [2/6] Verificação do MySQL
- Verifica se MySQL está disponível
- Continua mesmo se MySQL CLI não for encontrado
- Tenta conectar via PDO posteriormente

### [3/6] Configuração do Ambiente
- Cria arquivo `.env` se não existir
- Copia configurações do `.env.example`
- Preserva configurações existentes

### [4/6] Instalação de Dependências
- Verifica se existe `composer.json`
- Instala dependências via Composer se disponível
- Continua mesmo se Composer não estiver instalado

### [5/6] Configuração do Banco de Dados
- Cria banco de dados `dresscode` automaticamente
- Executa script `database/database.sql`
- Cria todas as tabelas necessárias
- Insere dados de teste

### [6/6] Inicialização do Servidor
- Inicia servidor PHP na porta 8000
- Exibe informações de acesso
- Fornece credenciais de teste

## 🛠️ Solução de Problemas

### PHP não encontrado
```bash
❌ PHP não encontrado! Instale o PHP ou use o Laragon.
```
**Solução:** Instale PHP ou inicie o Laragon/XAMPP

### Erro no banco de dados
```bash
⚠️ Erro no banco: Connection refused
```
**Solução:** Verifique se MySQL está rodando no Laragon/XAMPP

### Porta 8000 ocupada
**Solução:** Feche outros servidores ou altere a porta no `.env`:
```env
SERVER_PORT=8001
```

## 🔄 Migração do Sistema Antigo

### Scripts Removidos
- ❌ `RODAR-PROJETO.bat`
- ❌ `scripts/start-server-windows.bat`
- ❌ `scripts/start-server.bat`

### Novos Scripts
- ✅ `setup.bat` (Windows - Setup completo)
- ✅ `setup.sh` (Linux/macOS - Setup completo)
- ✅ `start.bat` (Windows - Início rápido)

## 🎯 Benefícios

### Para Desenvolvedores
- **Setup em 1 comando**: Não precisa configurar manualmente
- **Multiplataforma**: Funciona em qualquer SO
- **Portabilidade**: Projeto funciona imediatamente após clonar
- **Consistência**: Mesmo ambiente em todas as máquinas

### Para o Projeto
- **Manutenibilidade**: Configurações centralizadas no `.env`
- **Escalabilidade**: Fácil adicionar novas configurações
- **Segurança**: Arquivo `.env` não é versionado
- **Flexibilidade**: Configurações por ambiente

## 📝 Notas Importantes

1. **Primeira execução**: Sempre use `setup.bat` ou `setup.sh`
2. **Execuções subsequentes**: Use `start.bat` para início rápido
3. **Arquivo .env**: Não versione este arquivo (já está no .gitignore)
4. **Compatibilidade**: Funciona com Laragon, XAMPP, WAMP, MAMP
5. **Persistência**: Configurações mantidas entre reinicializações

## 🔗 Links Úteis

- **Acesso local**: http://localhost:8000
- **Login de teste**: teste@dresscode.com / 123456
- **Documentação**: README.md
- **Estrutura**: docs/ESTRUTURA.md