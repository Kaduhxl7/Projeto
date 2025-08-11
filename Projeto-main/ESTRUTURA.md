# 📁 Estrutura do Projeto DressCode

## 🎯 Organização Atual (Padronizada)

```
Projeto-main/
├── 📁 app/                          # Lógica da aplicação (MVC)
│   ├── 📁 config/                   # Configurações
│   │   ├── 📄 database.php          # Conexão com banco
│   │   └── 📄 config.php            # Configurações gerais
│   ├── 📁 controllers/              # Controladores (lógica)
│   │   └── 📄 AuthController.php    # Autenticação
│   ├── 📁 models/                   # Modelos (dados)
│   │   └── 📄 User.php              # Modelo de usuário
│   └── 📁 views/                    # Views (futuro)
│
├── 📁 public/                       # Arquivos públicos (ponto de entrada)
│   ├── 📁 assets/                   # Recursos estáticos
│   │   ├── 📁 css/                  # Estilos
│   │   │   └── 📄 style.css         # CSS principal
│   │   ├── 📁 js/                   # JavaScript
│   │   │   └── 📄 script.js         # JS principal
│   │   └── 📁 images/               # Imagens
│   │       ├── 🖼️ Logo.png          # Logo do projeto
│   │       ├── 🖼️ destaque1.jpg     # Imagens do carrossel
│   │       ├── 🖼️ destaque2.jpg
│   │       ├── 🖼️ destaque3.jpg
│   │       ├── 🖼️ bolsas.jpg        # Categorias
│   │       ├── 🖼️ personalizadas.jpg
│   │       ├── 🖼️ streetwear.jpg
│   │       └── 🖼️ [produtos...]     # Imagens de produtos
│   ├── 📄 .htaccess                 # Configurações Apache
│   ├── 📄 index.php                 # Página inicial
│   ├── 📄 login.php                 # Login
│   ├── 📄 cadastro.php              # Cadastro
│   ├── 📄 feminino.php              # Produtos femininos
│   └── 📄 logout.php                # Logout
│
├── 📁 includes/                     # Arquivos reutilizáveis
│   ├── 📄 header.php                # Cabeçalho comum
│   └── 📄 footer.php                # Rodapé comum
│
├── 📄 database.sql                  # Estrutura do banco
├── 📄 README.md                     # Documentação principal
├── 📄 INSTALL.md                    # Guia de instalação
└── 📄 ESTRUTURA.md                  # Este arquivo
```

## 🔄 Melhorias Implementadas

### ✅ Organização
- **Separação clara** entre lógica (app/), apresentação (public/) e reutilização (includes/)
- **Padrão MVC** implementado na estrutura
- **Assets organizados** por tipo (css, js, images)

### ✅ Padronização
- **Nomes consistentes** em português para arquivos principais
- **Estrutura de pastas** seguindo boas práticas
- **Arquivos de configuração** centralizados

### ✅ Segurança
- **Ponto de entrada único** através da pasta public/
- **Configurações protegidas** fora do diretório público
- **Validação e sanitização** de dados
- **Senhas criptografadas** com password_hash()

### ✅ Manutenibilidade
- **Código reutilizável** (header/footer)
- **Configuração centralizada** (database.php, config.php)
- **Documentação completa** (README, INSTALL)

## 🎨 Padrões Adotados

### Nomenclatura
- **Arquivos PHP**: PascalCase (AuthController.php)
- **Pastas**: lowercase (config/, models/)
- **Páginas**: lowercase (index.php, login.php)

### Estrutura de Código
- **Classes**: PascalCase
- **Métodos**: camelCase
- **Variáveis**: snake_case para banco, camelCase para PHP
- **Constantes**: UPPER_CASE

### Organização CSS
- **Mobile-first** approach
- **Variáveis CSS** para cores principais
- **Classes semânticas** e reutilizáveis

## 🚀 Próximos Passos

### Estrutura Futura
```
app/
├── controllers/
│   ├── AuthController.php
│   ├── ProductController.php       # Produtos
│   ├── UserController.php          # Perfil do usuário
│   └── AdminController.php         # Administração
├── models/
│   ├── User.php
│   ├── Product.php                 # Modelo de produtos
│   ├── Category.php                # Categorias
│   └── Store.php                   # Brechós
├── views/
│   ├── layouts/                    # Layouts base
│   ├── auth/                       # Páginas de autenticação
│   ├── products/                   # Páginas de produtos
│   └── admin/                      # Painel administrativo
└── middleware/                     # Middlewares de autenticação
```

## 📊 Comparação: Antes vs Depois

### ❌ Estrutura Anterior
```
Projeto-main/
├── Models/           # Misturava lógica e apresentação
├── View/             # Apenas 1 arquivo HTML
├── Style/            # CSS solto
├── js/               # JS solto
└── img/              # Imagens soltas
```

### ✅ Estrutura Atual
```
Projeto-main/
├── app/              # Lógica organizada (MVC)
├── public/           # Ponto de entrada seguro
├── includes/         # Código reutilizável
└── [docs]            # Documentação completa
```

## 🎯 Benefícios da Reorganização

1. **Segurança**: Arquivos sensíveis fora do público
2. **Escalabilidade**: Estrutura preparada para crescimento
3. **Manutenção**: Código organizado e documentado
4. **Performance**: Assets otimizados e cache configurado
5. **Colaboração**: Estrutura padrão facilita trabalho em equipe

---

**DressCode** - Agora com estrutura profissional! 🚀