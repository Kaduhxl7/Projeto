# Guia de Instalação - DressCode

## 📋 Pré-requisitos

- **PHP**: 7.4 ou superior
- **MySQL**: 5.7 ou superior (ou MariaDB 10.2+)
- **Servidor Web**: Apache ou Nginx
- **Extensões PHP necessárias**:
  - PDO
  - PDO_MySQL
  - mbstring
  - openssl

## 🚀 Instalação Passo a Passo

### 1. Preparação do Ambiente

#### XAMPP (Recomendado para desenvolvimento)
1. Baixe e instale o [XAMPP](https://www.apachefriends.org/)
2. Inicie os serviços Apache e MySQL
3. Acesse `http://localhost/phpmyadmin`

#### WAMP/LAMP
1. Configure seu ambiente WAMP/LAMP
2. Certifique-se de que Apache e MySQL estão rodando

### 2. Configuração do Banco de Dados

1. **Acesse o phpMyAdmin** ou seu cliente MySQL preferido

2. **Execute o script SQL**:
   ```sql
   -- Copie e execute o conteúdo do arquivo database.sql
   ```

3. **Ou importe o arquivo**:
   - No phpMyAdmin: Importar → Escolher arquivo → `database.sql`

### 3. Configuração do Projeto

1. **Clone/Baixe o projeto**:
   ```bash
   git clone [url-do-repositorio]
   # ou extraia o arquivo ZIP
   ```

2. **Mova para o diretório do servidor**:
   ```bash
   # XAMPP
   mv Projeto-main C:/xampp/htdocs/dresscode
   
   # WAMP
   mv Projeto-main C:/wamp64/www/dresscode
   
   # Linux
   sudo mv Projeto-main /var/www/html/dresscode
   ```

3. **Configure as permissões** (Linux/Mac):
   ```bash
   sudo chown -R www-data:www-data /var/www/html/dresscode
   sudo chmod -R 755 /var/www/html/dresscode
   ```

### 4. Configuração do Banco

1. **Edite o arquivo de configuração**:
   ```php
   // app/config/database.php
   private $host = 'localhost';
   private $db_name = 'dresscode';
   private $username = 'root';        // Seu usuário MySQL
   private $password = '';            // Sua senha MySQL
   ```

2. **Teste a conexão**:
   - Acesse `http://localhost/dresscode/public/`
   - Se aparecer a página inicial, está funcionando!

### 5. Configuração do Servidor Web

#### Apache (.htaccess já configurado)
- O arquivo `.htaccess` já está configurado na pasta `public/`
- Certifique-se de que `mod_rewrite` está habilitado

#### Nginx
```nginx
server {
    listen 80;
    server_name dresscode.local;
    root /var/www/html/dresscode/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🧪 Teste da Instalação

### 1. Acesse o projeto
- URL: `http://localhost/dresscode/public/`
- Ou: `http://localhost/[nome-da-pasta]/public/`

### 2. Teste o cadastro
1. Clique em "Entrar/Cadastrar"
2. Clique em "Não tenho uma conta"
3. Cadastre um novo usuário
4. Faça login

### 3. Navegue pelas páginas
- Página inicial: Carrossel e categorias
- Feminino: Produtos com filtros
- Busca: Digite algo e pressione Enter

## 🔧 Solução de Problemas

### Erro de Conexão com Banco
```
Erro na conexão: SQLSTATE[HY000] [1045] Access denied
```
**Solução**: Verifique usuário/senha em `app/config/database.php`

### Erro 500 - Internal Server Error
**Possíveis causas**:
1. Erro de sintaxe PHP
2. Permissões de arquivo incorretas
3. Configuração do servidor

**Solução**:
1. Verifique os logs de erro do Apache
2. Ative display_errors no PHP para desenvolvimento

### Imagens não carregam
**Solução**: Verifique se as imagens estão em `public/assets/images/`

### CSS/JS não carrega
**Solução**: Verifique os caminhos dos arquivos em `includes/header.php`

## 📱 Configuração para Produção

### 1. Segurança
```php
// app/config/config.php
error_reporting(0);
ini_set('display_errors', 0);
```

### 2. HTTPS
- Configure certificado SSL
- Descomente as linhas HTTPS no `.htaccess`

### 3. Banco de Dados
- Use credenciais seguras
- Configure backup automático

## 🆘 Suporte

Se encontrar problemas:

1. **Verifique os logs**:
   - Apache: `/var/log/apache2/error.log`
   - PHP: `/var/log/php_errors.log`

2. **Contato**:
   - Email: suportedresscode@dresscode.com
   - Issues no GitHub

## ✅ Checklist de Instalação

- [ ] PHP 7.4+ instalado
- [ ] MySQL rodando
- [ ] Banco `dresscode` criado
- [ ] Tabelas criadas (database.sql)
- [ ] Arquivos no diretório correto
- [ ] Configuração do banco ajustada
- [ ] Página inicial carregando
- [ ] Cadastro/Login funcionando
- [ ] Imagens carregando

---

**Parabéns! 🎉 Seu DressCode está funcionando!**