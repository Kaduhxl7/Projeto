# 🗄️ Scripts de Banco de Dados

Esta pasta contém todos os scripts SQL do projeto.

## 📁 Arquivos SQL

- `database.sql` - **Script principal** - Criação completa do banco
- `import-database.sql` - Script de importação
- `setup-database.sql` - Setup inicial do banco
- `fix-missing-tables.sql` - Correção de tabelas faltantes

## 🚀 Como usar

### Instalação completa:
```sql
-- Execute no phpMyAdmin ou MySQL
source database.sql;
```

### Ou via linha de comando:
```bash
mysql -u root -p < database.sql
```

## 📊 Estrutura do Banco

- **usuarios** - Dados dos usuários
- **categorias** - Categorias de produtos  
- **produtos** - Produtos cadastrados
- **favoritos** - Lista de favoritos
- **avaliacoes** - Avaliações dos produtos