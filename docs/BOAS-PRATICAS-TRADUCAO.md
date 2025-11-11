# 📋 Boas Práticas - Sistema de Tradução

## ✅ **REGRAS OBRIGATÓRIAS**

### 1. **Sempre Usar Funções de Tradução**
```php
// ❌ NUNCA faça isso
echo "Adicionar aos Favoritos";

// ✅ SEMPRE faça isso
echo __('product.add_to_favorites');
```

### 2. **Incluir Bootstrap em Novas Páginas**
```php
// ✅ Primeira linha de toda página PHP
require_once __DIR__ . '/../app/config/bootstrap.php';
```

### 3. **Traduzir Conteúdo Dinâmico**
```php
// ✅ Para dados do banco
echo TranslationHelper::translateDynamic($produto['nome']);

// ✅ Para preços formatados
echo TranslationHelper::formatPrice($produto['preco']);
```

## 🔧 **Checklist para Novas Funcionalidades**

- [ ] Bootstrap incluído no início da página
- [ ] Todos os textos usando `__()` ou `t()`
- [ ] Preços usando `TranslationHelper::formatPrice()`
- [ ] Nomes de produtos usando `TranslationHelper::translateDynamic()`
- [ ] Condições usando `__('conditions.xxx')`
- [ ] Categorias usando `__('categories.xxx')`
- [ ] Testado em todos os idiomas (PT, EN, ES, FR)

## 📝 **Estrutura de Chaves de Tradução**

```php
'section' => [
    'subsection' => [
        'key' => 'Valor traduzido'
    ]
]

// Exemplos:
'product.add_to_favorites'
'nav.home'
'search.filters'
'conditions.novo'
```

## 🚨 **Textos que DEVEM ser Traduzidos**

### Interface
- Botões, links, menus
- Mensagens de erro/sucesso
- Placeholders de formulários
- Títulos e subtítulos

### Dados Estruturados
- Condições de produtos (novo, usado, etc.)
- Categorias (feminino, masculino, etc.)
- Status do sistema
- Navegação breadcrumb

### Formatação
- Preços e moedas
- Datas e horários
- Números e unidades

## 🔍 **Como Identificar Textos Não Traduzidos**

1. **Teste Visual**: Mude o idioma e navegue pelo site
2. **Busca por Strings**: Procure por textos em português no código
3. **Verificar Console**: Chaves não encontradas aparecem como texto

## 📁 **Onde Adicionar Novas Traduções**

```
app/languages/
├── pt.php    (Português - referência)
├── en.php    (Inglês)
├── es.php    (Espanhol)
└── fr.php    (Francês)
```

## 🧪 **Testar Traduções**

1. Acesse: `/public/test-translation.php`
2. Teste cada idioma individualmente
3. Verifique se textos persistem entre páginas
4. Confirme formatação de preços por idioma

## ⚠️ **Erros Comuns a Evitar**

- Textos hardcoded em views
- Esquecer de incluir bootstrap
- Não traduzir dados do banco
- Usar formatação de preço fixa
- Não testar em todos os idiomas