# 🚀 Instalação Rápida - Sistema de Aceite de Termos

## ⚡ Passo a Passo

### 1️⃣ Atualizar o Banco de Dados

Execute um dos comandos abaixo:

**Opção A - Se o banco já existe:**
```bash
mysql -u root dresscode < database/add-aceite-termos.sql
```

**Opção B - Reinstalar banco completo:**
```bash
mysql -u root < database/dresscode_full_install.sql
```

### 2️⃣ Verificar Arquivos Criados

Certifique-se de que os seguintes arquivos foram criados:

```
✅ app/models/TermoAceite.php
✅ app/repositories/TermoRepository.php
✅ app/controllers/TermoController.php
✅ public/processar-aceite-termos.php
✅ public/privacidade.php (atualizado)
```

### 3️⃣ Testar o Sistema

1. **Iniciar o servidor:**
   ```bash
   cd public
   php -S localhost:8000
   ```

2. **Acessar página de teste:**
   ```
   http://localhost:8000/test-aceite-termos.php
   ```

3. **Testar o fluxo completo:**
   - Clicar em "Ir para Página de Privacidade"
   - Modal deve aparecer automaticamente
   - Marcar checkbox
   - Digitar nome completo
   - Clicar em "Concordo e Assino"
   - Verificar mensagem de sucesso

### 4️⃣ Verificar no Banco

```sql
USE dresscode;
SELECT * FROM aceite_termos;
```

## 🎯 Funcionalidades Implementadas

✅ Modal moderno e responsivo  
✅ Exibição de 2 PDFs (Termo de Uso e Consentimento)  
✅ Checkbox obrigatório  
✅ Campo de assinatura digital  
✅ Validação frontend e backend  
✅ Registro em banco de dados  
✅ Bloqueio de acesso até aceitar  
✅ SweetAlert2 para mensagens  
✅ Design seguindo padrão DressCode  

## 📁 Estrutura de Arquivos

```
Projeto-master/
├── app/
│   ├── controllers/
│   │   └── TermoController.php          ← Novo
│   ├── models/
│   │   └── TermoAceite.php              ← Novo
│   └── repositories/
│       └── TermoRepository.php          ← Novo
├── database/
│   ├── dresscode_full_install.sql       ← Atualizado
│   └── add-aceite-termos.sql            ← Novo
├── docs/
│   └── SISTEMA-ACEITE-TERMOS.md         ← Novo
└── public/
    ├── privacidade.php                  ← Atualizado
    ├── processar-aceite-termos.php      ← Novo
    ├── test-aceite-termos.php           ← Novo (teste)
    ├── resetar-aceite-teste.php         ← Novo (teste)
    └── assets/
        └── pdfs/
            ├── DRESSCODE - MODELO DE TERMO DE USO DO SISTEMA.docx.pdf
            └── DRESSCODE - MODELO DE TERMO DE CONSENTIMENTO PARA TRATAMENTO DE DADOS PESSOAIS.docx.pdf
```

## 🔧 Configuração

### Personalizar Cores

Edite em `public/privacidade.php`:

```css
/* Cor principal */
background: #5e2b2b;

/* Cor do hover */
background: #4a2323;
```

### Alterar PDFs

Substitua os arquivos em `public/assets/pdfs/` e atualize os caminhos em `privacidade.php`.

### Adicionar Canvas de Assinatura

Substitua o input de texto por um canvas HTML5 para desenho manual.

## 🐛 Solução de Problemas

### Modal não aparece
```sql
-- Verificar se já existe aceite
SELECT * FROM aceite_termos WHERE id_usuario = 1;

-- Remover para testar novamente
DELETE FROM aceite_termos WHERE id_usuario = 1;
```

### Erro de conexão
- Verificar se o banco `dresscode` existe
- Verificar credenciais em `app/config/database.php`

### PDFs não carregam
- Verificar se os arquivos existem em `public/assets/pdfs/`
- Testar URL direta: `http://localhost:8000/assets/pdfs/...`

## 📊 Consultas Úteis

```sql
-- Ver todos os aceites
SELECT u.nome, u.email, a.data_aceite, a.assinatura 
FROM aceite_termos a
JOIN usuarios u ON a.id_usuario = u.id;

-- Usuários que não aceitaram
SELECT u.nome, u.email 
FROM usuarios u
LEFT JOIN aceite_termos a ON u.id = a.id_usuario
WHERE a.id IS NULL;

-- Aceites nas últimas 24h
SELECT COUNT(*) as total 
FROM aceite_termos 
WHERE data_aceite >= DATE_SUB(NOW(), INTERVAL 24 HOUR);
```

## ✅ Checklist Final

- [ ] Banco de dados atualizado
- [ ] Todos os arquivos criados
- [ ] Servidor rodando
- [ ] Teste realizado com sucesso
- [ ] Modal aparece corretamente
- [ ] PDFs carregam
- [ ] Aceite é registrado no banco
- [ ] Mensagem de sucesso aparece

## 📞 Suporte

Consulte a documentação completa em `docs/SISTEMA-ACEITE-TERMOS.md`

---

**Desenvolvido para DressCode** 🛍️  
Sistema de aceite de termos com modal moderno e registro em banco de dados.
