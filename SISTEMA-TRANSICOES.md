# 🎨 Sistema de Transições de Página - DressCode

## ✨ Funcionalidades Implementadas

✅ **Transições automáticas** entre todas as páginas do site  
✅ **Overlay da marca** com logo e nome durante a transição  
✅ **Animações suaves** com fade-in, fade-out e zoom  
✅ **Responsivo** - funciona em desktop e mobile  
✅ **Tema escuro** - adapta-se automaticamente  
✅ **Configurável** - fácil personalização  
✅ **Performance otimizada** - JavaScript puro, sem frameworks  

## 📁 Arquivos Criados

```
includes/
├── transition-brand-overlay.php    # Componente do overlay da marca

public/assets/
├── css/
│   └── page-transitions.css        # Estilos das transições
└── js/
    └── pageTransition.js          # Lógica das transições

app/config/
└── transitions.php                # Configurações personalizáveis

public/
└── test-transitions.php          # Página de teste
```

## 🚀 Como Funciona

1. **Interceptação automática**: O JavaScript intercepta todos os cliques em links de navegação
2. **Animação de saída**: O conteúdo atual desaparece suavemente
3. **Overlay da marca**: Aparece o logo e nome da marca com animação
4. **Navegação**: Redireciona para a nova página
5. **Animação de entrada**: A nova página aparece suavemente

## ⚙️ Personalização

### 1. Nome da Marca
Edite `app/config/transitions.php`:
```php
'brand_name' => 'Seu Nome Aqui',
```

### 2. Cores do Gradiente
```php
'background_gradient' => [
    'start' => '#sua-cor-inicial',
    'end' => '#sua-cor-final'
],
```

### 3. Duração da Transição
```php
'transition_duration' => 1000, // em milissegundos
```

### 4. Links Ignorados
```php
'skip_links' => [
    'logout.php',
    'seu-link-especial.php'
],
```

## 🧪 Teste

Acesse: `http://localhost/Projeto/Projeto-master/public/test-transitions.php`

## 🎯 Comportamento

### Links Interceptados:
- ✅ Navegação principal (Início, Mulher, Homem, etc.)
- ✅ Cards clicáveis
- ✅ Links internos (.php)

### Links Ignorados:
- ❌ Links externos (http://, https://)
- ❌ Âncoras (#)
- ❌ Logout
- ❌ AJAX (toggle-favorito, etc.)
- ❌ Mudança de idioma

## 📱 Responsividade

- **Desktop**: Logo 80px, texto 2.5rem
- **Tablet**: Logo 60px, texto 2rem  
- **Mobile**: Logo 50px, texto 1.5rem

## 🌙 Tema Escuro

O sistema detecta automaticamente o tema escuro e ajusta:
- Gradiente escuro (#1a1a1a → #2d2d2d)
- Logo invertido para branco
- Texto branco

## 🔧 Integração

O sistema foi integrado automaticamente:

1. **CSS** incluído no `header.php`
2. **Overlay** inserido no `body`
3. **JavaScript** carregado no `footer.php`
4. **Configurações** centralizadas

## ⚡ Performance

- **Leve**: ~3KB CSS + ~4KB JS
- **Rápido**: Transições de 800ms
- **Otimizado**: Sem bibliotecas externas
- **Compatível**: Todos os navegadores modernos

## 🎨 Efeito Visual

```
Clique no link → Conteúdo desaparece → Overlay aparece → 
Logo + Nome animam → Overlay desaparece → Nova página carrega
```

**Duração total**: 800ms (personalizável)

## 🛠️ Manutenção

Para desabilitar temporariamente:
1. Comente a linha no `header.php`: `<!-- <link rel="stylesheet" href="assets/css/page-transitions.css"> -->`
2. Comente a linha no `footer.php`: `<!-- <script src="assets/js/pageTransition.js"></script> -->`

## ✅ Status

🟢 **IMPLEMENTADO E FUNCIONANDO**

- Sistema totalmente funcional
- Integrado ao projeto MVC
- Testado em múltiplas páginas
- Responsivo e acessível
- Configurável e personalizável