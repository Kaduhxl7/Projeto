# 🚀 Como Executar o DressCode

## ⚡ Execução Rápida

### 1. Configurar Banco de Dados
```sql
-- No MySQL/phpMyAdmin, execute:
CREATE DATABASE dresscode;
USE dresscode;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário de teste (email: teste@dresscode.com, senha: 123456)
INSERT INTO usuarios (email, senha, nome) VALUES 
('teste@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste');
```

### 2. Iniciar Servidor
```bash
# Opção 1: Script automático (Windows)
start-server.bat

# Opção 2: Manual
cd public
php -S localhost:8000
```

### 3. Acessar Aplicação
- **URL**: http://localhost:8000
- **Login de teste**: teste@dresscode.com / 123456

## 📱 Funcionalidades Disponíveis

✅ **Página Inicial** - Carrossel e categorias  
✅ **Cadastro** - Criar nova conta  
✅ **Login** - Entrar na conta  
✅ **Catálogo Feminino** - Produtos com filtros  
✅ **Busca** - Sistema de busca inteligente  
✅ **Responsivo** - Funciona em mobile  

## 🔧 Configuração do Banco

Edite `app/config/database.php` se necessário:
```php
private $host = 'localhost';
private $db_name = 'dresscode';  
private $username = 'root';
private $password = '';
```

## 🎯 Testando a Aplicação

1. **Página Inicial**: Navegue pelo carrossel
2. **Cadastro**: Crie uma conta nova
3. **Login**: Use teste@dresscode.com / 123456
4. **Produtos**: Acesse "Feminino" e veja o catálogo
5. **Busca**: Digite algo na barra de busca

---
**DressCode** - Pronto para usar! 🎉