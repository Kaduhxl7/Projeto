# DressCode - Sistema de Brechós Online

**Autor Principal:** Kauan Alves Coelho

## Grupo:
- Maria Eduarda Oliveira - Líder/22301275
- Maria Eduarda Rosa/22402080
- Kauan Alves Coelho/22302255
- Giovana Tassi/22403094
- Nicolas Peres/22302409
- Moises/22301313

## 📁 Estrutura do Projeto

```
DressCode/
├── app/                    # Arquitetura MVC Principal
│   ├── config/             # Configurações do sistema
│   ├── controllers/        # Controladores MVC
│   ├── models/             # Modelos de dados
│   ├── views/              # Views/Templates
│   ├── entities/           # Entidades do domínio
│   ├── helpers/            # Classes auxiliares
│   ├── languages/          # Arquivos de idioma
│   └── repositories/       # Repositórios de dados
├── public/                 # Arquivos públicos (entry point)
│   ├── assets/             # CSS, JS, imagens
│   └── *.php               # Páginas públicas
├── database/               # Scripts SQL
├── scripts/                # Scripts de inicialização (.bat, .sh)
├── docs/                   # Documentação (.md, .pdf)
├── tests/                  # Arquivos de teste
├── temp/                   # Arquivos temporários
├── backups/                # Backups e arquivos compactados
├── resources/              # Recursos auxiliares
├── utils/                  # Utilitários diversos
├── includes/               # Arquivos de inclusão
└── examples/               # Exemplos de uso
```

## Requisitos do sistema implementados:

✅ **O sistema deve permitir o cadastro de brechós** - Feito

✅ **O dono do brechó deve poder cadastrar produtos à venda** - Feito

✅ **O sistema deve permitir a aplicação de filtros de busca (tamanho, cor, tipo)** - Feito

✅ **Os usuários devem poder visualizar detalhes dos produtos** - Feito

✅ **O usuário deve poder criar uma conta e gerenciar seu perfil** - Feito

✅ **Deve ser possível avaliar e comentar sobre os produtos comprados** - Feito

✅ **Os usuários devem poder salvar produtos em uma lista de desejos** - Feito

✅ **O sistema deve permitir a personalização das configurações da interface pelo usuário** - Feito

✅ **O site deve ter uma área dedicada a promoções e ofertas especiais** - Feito

✅ **O sistema deve garantir a segurança dos dados pessoais dos usuários e donos de brechós** - Feito

✅ **O sistema deve permitir que os donos dos brechós atualizem seus anúncios** - Implementado

✅ **Deve haver um sistema de pagamento online para a taxa de anúncio.** - Implementado

✅ **O sistema deve enviar notificações sobre novos produtos ou promoções.** - Implementado

✅ **O sistema deve oferecer suporte a múltiplos idiomas.** - Implementado

✅ **Deve haver um dashboard para os donos dos brechós visualizarem suas vendas e lucros.** - Implementado

✅ **O sistema deve permitir a exportação de relatórios de vendas em PDF.** - Implementado

✅ **Deve haver um sistema de busca eficiente para encontrar brechós por localização** - Implementado

✅ **O site deve ter uma seção de perguntas frequentes (FAQ).** - Implementado

**Os usuários devem poder compartilhar produtos nas redes sociais.** - Pendente

✅ **Deve haver um mecanismo para reportar problemas com produtos ou anúncios.** - Implementado 

## Novas Funcionalidades Implementadas:

🆕 **Sistema de Cadastro Completo**
Separação entre Comprador (Usuário) e Vendedor (Brechó)
Campos: Nome, Sobrenome, Usuário, Celular
Upload de foto de perfil/banner
Campos condicionais para dados do brechó
Campos condicionais para endereço de entrega
Máscaras para celular e CEP
Busca automática de endereço via ViaCEP

🆕 **Sistema de Configurações**
Personalização de tema (claro/escuro)
Configuração de cores
Ajuste de tamanho de fonte
Preferências de layout

🆕 **Sistema de Segurança LGPD**
Log de tentativas de login
Controle de consentimentos
Exportação de dados pessoais
Exclusão de conta

🆕 **Sistema de Inicialização Automatizado**
Setup multiplataforma (Windows/Linux/macOS)
Configuração automática do banco de dados
Gerenciamento de variáveis de ambiente (.env)
Detecção automática de dependências
Iniciação do servidor com um comando
Compatibilidade universal entre máquinas

## Como Executar:

### Inicialização Automática (Recomendado)

**Windows:**
```bash
setup.bat
```

**Linux/macOS:**
```bash
bash setup.sh
```

### O que o script faz automaticamente:
- ✅ Verifica se PHP e MySQL estão instalados
- ✅ Cria o arquivo .env baseado no .env.example
- ✅ Instala dependências (se houver composer.json)
- ✅ Cria o banco de dados MySQL automaticamente
- ✅ Executa o script database.sql para criar tabelas
- ✅ Inicia o servidor PHP na porta 8000
- ✅ Exibe informações de acesso

### Acesso:
- 🌐 **URL:** http://localhost:8000
- 🔐 **Login de teste:** teste@dresscode.com / 123456

### Execução Manual (se necessário):
```bash
cd public
php -S localhost:8000
```

### Compatibilidade:
- ✅ Windows (Laragon, XAMPP, WAMP)
- ✅ Linux (Ubuntu, Debian, etc.)
- ✅ macOS (MAMP, Homebrew)
- ✅ Funciona imediatamente após clonar/copiar o projeto
