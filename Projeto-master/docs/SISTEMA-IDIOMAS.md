# 🌍 Sistema de Múltiplos Idiomas - DressCode

## ✅ Implementação Completa

O sistema de internacionalização foi implementado com suporte a **4 idiomas**:
- 🇧🇷 **Português** (pt) - Padrão
- 🇺🇸 **English** (en)
- 🇪🇸 **Español** (es)  
- 🇫🇷 **Français** (fr)

## 🎯 Funcionalidades

### ✅ Detecção Automática
- **Prioridade**: URL > Sessão > Cookie > Navegador > Padrão
- Detecta idioma do navegador automaticamente
- Salva preferência em sessão e cookie

### ✅ Seletor Visual
- Dropdown elegante no header
- Bandeiras e nomes dos idiomas
- Interface responsiva
- Mudança instantânea

### ✅ Persistência
- Salva em `$_SESSION['language']`
- Cookie com validade de 1 ano
- Mantém idioma entre sessões

## 🚀 Como Usar

### 1. Inicializar o Sistema
```php
<?php
session_start();
require_once __DIR__ . '/../app/config/Language.php';
$lang = Language::getInstance();
?>
```

### 2. Usar Traduções
```php
// Função global
echo __('nav.home');           // "Início" / "Home" / "Inicio" / "Accueil"
echo __('site.title');         // Título do site
echo __('product.price');      // "Preço" / "Price" / "Precio" / "Prix"

// Com parâmetros
echo __('messages.welcome', ['name' => 'João']); // "Bem-vindo, João"
```

### 3. Incluir Seletor de Idioma
```php
<?php include __DIR__ . '/language-selector.php'; ?>
```

## 📁 Estrutura de Arquivos

```
app/
├── config/
│   └── Language.php          # Classe principal
├── languages/
│   ├── pt.php               # Português (padrão)
│   ├── en.php               # English
│   ├── es.php               # Español
│   └── fr.php               # Français
includes/
└── language-selector.php     # Componente seletor
```

## 🎨 Estrutura das Traduções

### Organização por Seções
```php
return [
    'site' => [
        'title' => 'DressCode - Moda Sustentável',
        'description' => 'Descubra peças únicas...'
    ],
    'nav' => [
        'home' => 'Início',
        'women' => 'Feminino',
        'men' => 'Masculino'
    ],
    'product' => [
        'price' => 'Preço',
        'condition' => 'Condição'
    ]
];
```

### Acesso Hierárquico
```php
__('nav.home')           // Navegação > Home
__('product.price')      // Produto > Preço
__('messages.success')   // Mensagens > Sucesso
```

## 🔧 Métodos Disponíveis

### Language::getInstance()
```php
$lang = Language::getInstance();
```

### Obter Tradução
```php
$lang->get('nav.home');
__('nav.home');  // Função global
```

### Idioma Atual
```php
$lang->getCurrentLanguage();     // 'pt', 'en', 'es', 'fr'
getCurrentLang();                // Função global
```

### Idiomas Disponíveis
```php
$lang->getAvailableLanguages();
// ['pt' => ['name' => 'Português', 'flag' => '🇧🇷'], ...]
```

### Mudar Idioma
```php
$lang->setLanguage('en');  // Muda para inglês
```

## 🌐 URLs com Idioma

### Mudança via URL
```
index.php?lang=en        # Muda para inglês
categoria.php?cat=feminino&lang=es  # Espanhol + categoria
```

### Manter Parâmetros
O seletor preserva todos os parâmetros da URL atual:
```php
// Em categoria.php?cat=feminino&search=blusa
// Ao mudar idioma: categoria.php?cat=feminino&search=blusa&lang=en
```

## 📱 Interface Responsiva

### Desktop
- Seletor no canto superior direito
- Dropdown com bandeiras e nomes
- Hover effects suaves

### Mobile
- Posicionamento otimizado
- Tamanhos ajustados
- Touch-friendly

## 🎯 Páginas Atualizadas

### ✅ Já Implementadas
- `index.php` - Página inicial
- `includes/header.php` - Navegação
- Sistema base funcionando

### 📝 Para Implementar
```php
// Em cada página PHP, adicionar no início:
require_once __DIR__ . '/../app/config/Language.php';
$lang = Language::getInstance();

// Substituir textos fixos por:
echo __('chave.traducao');
```

## 🔄 Exemplo de Implementação

### Antes
```php
<h2>Destaques da Semana</h2>
<a href="categoria.php?cat=feminino">Feminino</a>
```

### Depois
```php
<h2><?php echo __('home.highlights_title'); ?></h2>
<a href="categoria.php?cat=feminino"><?php echo __('nav.women'); ?></a>
```

## 🎨 Personalização

### Adicionar Novo Idioma
1. Criar arquivo `app/languages/xx.php`
2. Adicionar em `Language.php`:
```php
'xx' => ['name' => 'Nome', 'flag' => '🏳️']
```

### Adicionar Nova Tradução
```php
// Em todos os arquivos de idioma
'nova_secao' => [
    'nova_chave' => 'Texto traduzido'
]
```

## 🚀 Status Atual

### ✅ Implementado
- ✅ Sistema base de idiomas
- ✅ 4 idiomas completos
- ✅ Seletor visual no header
- ✅ Detecção automática
- ✅ Persistência em sessão/cookie
- ✅ Página inicial traduzida
- ✅ Navegação traduzida

### 📋 Próximos Passos
1. Traduzir páginas de categoria
2. Traduzir página de produto
3. Traduzir formulários de login/cadastro
4. Traduzir mensagens de erro/sucesso
5. Traduzir filtros e ordenação

## 🎉 Resultado

O DressCode agora suporta **múltiplos idiomas** com:
- 🌍 **4 idiomas** disponíveis
- 🎯 **Detecção automática** do navegador
- 💾 **Persistência** entre sessões
- 🎨 **Interface elegante** e responsiva
- ⚡ **Mudança instantânea** de idioma

**Acesse**: http://localhost:8000 e teste o seletor de idiomas! 🚀