# 📋 Sistema de Aceite de Termos - DressCode

## 📌 Visão Geral

Sistema completo de aceite de termos de uso e privacidade com modal moderno, exibição de PDFs e registro em banco de dados.

## 🗂️ Arquivos Criados

### Backend (PHP)
- `/app/models/TermoAceite.php` - Model para gerenciar dados de aceite
- `/app/repositories/TermoRepository.php` - Repository para operações de banco
- `/app/controllers/TermoController.php` - Controller para lógica de negócio
- `/public/processar-aceite-termos.php` - Endpoint AJAX

### Frontend
- `/public/privacidade.php` - Página atualizada com modal integrado

### Banco de Dados
- Tabela `aceite_termos` adicionada ao `dresscode_full_install.sql`

## 🗄️ Estrutura da Tabela

```sql
CREATE TABLE aceite_termos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    termos_aceitos BOOLEAN DEFAULT FALSE,
    data_aceite DATETIME DEFAULT CURRENT_TIMESTAMP,
    assinatura TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_aceite (id_usuario)
);
```

## 🚀 Como Funciona

### 1. Verificação Automática
- Ao acessar `/public/privacidade.php`, o sistema verifica se o usuário já aceitou os termos
- Se não aceitou, o modal é exibido automaticamente

### 2. Modal Interativo
- Exibe dois PDFs lado a lado (Termo de Uso e Termo de Consentimento)
- Checkbox obrigatório para aceite
- Campo de assinatura digital (nome completo)
- Botão desabilitado até preencher todos os campos

### 3. Registro no Banco
- Ao clicar em "Concordo e Assino", os dados são enviados via AJAX
- Sistema registra:
  - ID do usuário
  - Data/hora do aceite
  - Assinatura (nome completo)
  - IP do usuário
  - User Agent (navegador)

### 4. Confirmação
- Modal fecha automaticamente
- SweetAlert2 exibe mensagem de sucesso
- Usuário pode continuar navegando

## 🎨 Design

### Características Visuais
- ✅ Tema claro com cores do DressCode (#5e2b2b)
- ✅ Bordas arredondadas (border-radius: 20px)
- ✅ Sombras suaves e modernas
- ✅ Animação de entrada (slideDown)
- ✅ Backdrop blur no fundo
- ✅ Responsivo para mobile

### Fonte
- Fonte padrão do sistema
- Campo de assinatura usa fonte cursiva (Brush Script MT)

## 📱 Responsividade

- Desktop: PDFs lado a lado
- Mobile: PDFs empilhados verticalmente
- Altura dos iframes ajustável

## 🔒 Segurança

- Validação de sessão (usuário logado)
- Validação de dados no backend
- Registro de IP e User Agent
- Proteção contra duplicatas (UNIQUE KEY)
- Sanitização de inputs

## 🧪 Como Testar

1. **Executar SQL**
   ```bash
   # Importar o arquivo atualizado
   mysql -u root dresscode < database/dresscode_full_install.sql
   ```

2. **Acessar a página**
   ```
   http://localhost:8000/privacidade.php
   ```

3. **Testar o fluxo**
   - Login com usuário teste
   - Acessar página de privacidade
   - Modal deve aparecer automaticamente
   - Marcar checkbox
   - Digitar nome completo
   - Clicar em "Concordo e Assino"
   - Verificar mensagem de sucesso

4. **Verificar no banco**
   ```sql
   SELECT * FROM aceite_termos;
   ```

## 🔧 Personalização

### Alterar PDFs
Substitua os arquivos em `/public/assets/pdfs/`:
- `DRESSCODE - MODELO DE TERMO DE USO DO SISTEMA.docx.pdf`
- `DRESSCODE - MODELO DE TERMO DE CONSENTIMENTO PARA TRATAMENTO DE DADOS PESSOAIS.docx.pdf`

### Alterar Cores
Edite as variáveis CSS em `privacidade.php`:
```css
background: #5e2b2b; /* Cor principal */
border-color: #5e2b2b; /* Bordas */
```

### Adicionar Canvas de Assinatura
Substitua o campo de texto por um canvas HTML5 para desenho de assinatura.

## 📊 Relatórios

Para gerar relatórios de aceites:

```sql
-- Usuários que aceitaram
SELECT u.nome, u.email, a.data_aceite, a.assinatura 
FROM aceite_termos a
JOIN usuarios u ON a.id_usuario = u.id
WHERE a.termos_aceitos = 1;

-- Usuários que ainda não aceitaram
SELECT u.nome, u.email 
FROM usuarios u
LEFT JOIN aceite_termos a ON u.id = a.id_usuario
WHERE a.id IS NULL OR a.termos_aceitos = 0;
```

## ✅ Checklist de Implementação

- [x] Tabela no banco de dados
- [x] Model TermoAceite
- [x] Repository TermoRepository
- [x] Controller TermoController
- [x] Endpoint AJAX
- [x] Modal HTML/CSS
- [x] JavaScript para interação
- [x] Integração com SweetAlert2
- [x] Validações frontend e backend
- [x] Design responsivo
- [x] Documentação

## 🐛 Troubleshooting

### Modal não aparece
- Verificar se usuário está logado
- Verificar se já existe registro na tabela `aceite_termos`
- Limpar cache do navegador

### Erro ao salvar
- Verificar conexão com banco
- Verificar se tabela foi criada
- Verificar logs do PHP

### PDFs não carregam
- Verificar se arquivos existem em `/public/assets/pdfs/`
- Verificar permissões de leitura
- Testar URL direta no navegador

## 📞 Suporte

Para dúvidas ou problemas, consulte a documentação do projeto ou entre em contato com a equipe de desenvolvimento.
