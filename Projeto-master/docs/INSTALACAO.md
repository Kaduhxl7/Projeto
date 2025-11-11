# 🚀 DressCode - Guia de Instalação

## 📋 Pré-requisitos

### 1. Instalar Laragon (Recomendado)
- Baixe em: https://laragon.org/download/
- Instale com as configurações padrão
- **OU** use XAMPP/WAMP como alternativa

### 2. Verificar Componentes
- ✅ PHP 8.0+
- ✅ MySQL 8.0+
- ✅ Apache/Nginx

## 🔧 Instalação Passo a Passo

### 1. Clonar o Repositório
```bash
git clone https://github.com/Kaduhxl7/Projeto.git
cd Projeto
```

### 2. Configurar Banco de Dados

#### Opção A - Automática (Recomendada)
```bash
# Execute o script de configuração
php setup-quick.php
```

#### Opção B - Manual
1. Abra phpMyAdmin: `http://localhost/phpmyadmin`
2. Importe o arquivo: `import-database.sql`
3. Ou execute os comandos SQL do arquivo `database.sql`

### 3. Configurar Conexão do Banco

Edite o arquivo `app/config/database.php`:

```php
private $host = 'localhost';
private $db_name = 'dresscode';
private $username = 'root';
private $password = ''; // Altere conforme sua configuração
```

**Senhas comuns:**
- Laragon: `''` (vazio) ou `root`
- XAMPP: `''` (vazio)
- WAMP: `''` (vazio) ou `root`

### 4. Iniciar o Servidor

#### Windows (Laragon/XAMPP)
```bash
# Navegue até a pasta public
cd public
php -S localhost:8000
```

#### Ou use os scripts prontos:
```bash
# Windows
start-server-windows.bat

# Ou
start-server.bat
```

### 5. Acessar o Sistema
- **URL**: http://localhost:8000
- **Login de teste**: teste@dresscode.com
- **Senha**: 123456

## 🗄️ Estrutura do Banco de Dados

O sistema criará automaticamente as seguintes tabelas:

- `usuarios` - Dados dos usuários
- `categorias` - Categorias de produtos
- `produtos` - Produtos cadastrados
- `favoritos` - Lista de favoritos
- `avaliacoes` - Avaliações dos produtos
- `tentativas_login` - Log de segurança
- `logs_seguranca` - Logs do sistema
- `consentimentos_lgpd` - Consentimentos LGPD

## 🔍 Solução de Problemas

### Erro: "Access denied for user 'root'"
1. Verifique a senha no arquivo `app/config/database.php`
2. Teste senhas comuns: `''`, `root`, `1234`, `password`
3. Ou redefina a senha do MySQL

### Erro: "Table doesn't exist"
1. Execute: `php setup-quick.php`
2. Ou importe manualmente: `import-database.sql`

### Erro: "could not find driver"
1. Verifique se a extensão PDO MySQL está habilitada
2. No php.ini, descomente: `extension=pdo_mysql`

### Imagens não aparecem
1. Verifique se as imagens estão em: `public/assets/images/`
2. Execute: `php check-files.php` para diagnóstico

## 📱 Funcionalidades Disponíveis

- ✅ Sistema de login/cadastro
- ✅ Categorias: Feminino, Masculino, Infantil, Acessórios
- ✅ Busca avançada com filtros
- ✅ Efeitos hover elegantes
- ✅ Sistema de favoritos
- ✅ Avaliações de produtos
- ✅ Painel de configurações
- ✅ Conformidade LGPD

## 🌐 URLs Principais

- **Início**: http://localhost:8000/
- **Login**: http://localhost:8000/login.php
- **Cadastro**: http://localhost:8000/cadastro.php
- **Feminino**: http://localhost:8000/categoria.php?cat=feminino
- **Masculino**: http://localhost:8000/categoria.php?cat=masculino
- **Busca**: http://localhost:8000/busca.php

## 🔐 Dados de Teste

**Usuário**: teste@dresscode.com  
**Senha**: 123456

## 📞 Suporte

Se encontrar problemas:
1. Execute `php check-files.php` para diagnóstico
2. Verifique os logs de erro do PHP
3. Confirme se o Laragon/XAMPP está rodando

---

**Desenvolvido por**: Programador dresscode  
**Versão**: 1.0.0  
**Tecnologias**: PHP, MySQL, HTML5, CSS3, JavaScript