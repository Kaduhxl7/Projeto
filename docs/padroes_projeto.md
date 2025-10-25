# Padrões de Projeto GoF Implementados

Este documento descreve os padrões de projeto (Design Patterns) implementados no sistema de brechós/marketplace, seguindo os padrões GoF (Gang of Four).

## 📋 Resumo dos Padrões Implementados

| Padrão | Categoria | Localização | Justificativa |
|--------|-----------|-------------|---------------|
| **Singleton** | Criacional | `app/config/DatabaseConnection.php` | Garante uma única instância de conexão com banco |
| **Factory Method** | Criacional | `app/factories/ProductFactory.php` | Cria diferentes tipos de produtos baseados na condição |
| **Observer** | Comportamental | `app/observers/` | Desacopla sistema de notificações dos controladores |
| **Strategy + Decorator** | Comportamental | `app/search/` | Sistema flexível de busca com filtros dinâmicos |

---

## 🔧 1. Singleton Pattern

### **Onde foi aplicado:**
- `app/config/DatabaseConnection.php`
- Utilizado em todos os models: `Product.php`, `User.php`, `Brecho.php`

### **Justificativa técnica:**
O Singleton foi aplicado exclusivamente na conexão com banco de dados para:
- **Evitar múltiplas conexões**: Garante que apenas uma instância PDO seja criada durante toda a execução
- **Melhorar performance**: Reduz overhead de criação de conexões desnecessárias
- **Controlar recursos**: Gerencia eficientemente o pool de conexões do banco
- **Consistência**: Todas as operações usam a mesma configuração de conexão

### **Implementação:**
```php
class DatabaseConnection {
    private static ?PDO $instance = null;
    
    private function __construct() {} // Construtor privado
    private function __clone() {}     // Previne clonagem
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::initializeConnection();
        }
        return self::$instance;
    }
}
```

### **Benefícios obtidos:**
- ✅ Redução no uso de memória
- ✅ Controle centralizado da conexão
- ✅ Facilita debugging e monitoramento
- ✅ Previne problemas de concorrência

---

## 🏭 2. Factory Method Pattern

### **Onde foi aplicado:**
- `app/factories/ProductFactory.php`
- `app/entities/Product.php` (entidade atualizada)

### **Justificativa técnica:**
O Factory Method foi implementado para criar diferentes tipos de produtos porque:
- **Flexibilidade**: Permite adicionar novos tipos de produtos sem modificar código existente
- **Encapsulamento**: Centraliza a lógica de criação de produtos
- **Polimorfismo**: Diferentes tipos de produtos podem ter comportamentos específicos
- **Manutenibilidade**: Facilita mudanças na lógica de criação

### **Tipos de produtos suportados:**
- **Produto Novo**: Sem desconto, com garantia, alta prioridade
- **Produto Usado**: 10% desconto padrão, sem garantia, prioridade média
- **Produto Promocional**: Desconto configurável, com garantia, máxima prioridade
- **Produto Vintage**: Sem desconto, sem garantia, alta prioridade especial

### **Implementação:**
```php
abstract class ProductFactory {
    public static function createProduct(array $data): Product {
        $condicao = $data['condicao'] ?? 'usado';
        
        switch (strtolower($condicao)) {
            case 'novo': return self::createNewProduct($data);
            case 'usado': return self::createUsedProduct($data);
            case 'promocional': return self::createPromotionalProduct($data);
            case 'vintage': return self::createVintageProduct($data);
        }
    }
}
```

### **Benefícios obtidos:**
- ✅ Código mais limpo e organizado
- ✅ Fácil adição de novos tipos de produtos
- ✅ Comportamentos específicos por tipo
- ✅ Melhor testabilidade

---

## 👁️ 3. Observer Pattern

### **Onde foi aplicado:**
- `app/observers/Observer.php` (interface)
- `app/observers/Subject.php` (interface)
- `app/observers/ProductPublisher.php` (subject)
- `app/observers/NotificationObserver.php` (observer concreto)
- `app/controllers/NotificationController.php` (integração)

### **Justificativa técnica:**
O Observer foi implementado no sistema de notificações para:
- **Desacoplamento**: Separa a lógica de negócio da criação de notificações
- **Extensibilidade**: Permite adicionar novos tipos de observadores facilmente
- **Responsabilidade única**: Cada observador tem uma responsabilidade específica
- **Flexibilidade**: Observadores podem ser adicionados/removidos dinamicamente

### **Eventos suportados:**
- **new_product**: Quando um novo produto é adicionado
- **promotion**: Quando uma promoção é criada
- **store_update**: Quando um brechó é atualizado
- **product_promotion**: Quando um produto entra em promoção

### **Implementação:**
```php
// Publisher (Subject)
class ProductPublisher implements Subject {
    private array $observers = [];
    
    public function attach(Observer $observer): void { /* ... */ }
    public function notify(string $eventType, array $data): void { /* ... */ }
}

// Observer concreto
class NotificationObserver implements Observer {
    public function update(string $eventType, array $data): void {
        // Processa evento e cria notificações no banco
    }
}
```

### **Benefícios obtidos:**
- ✅ Sistema de notificações desacoplado
- ✅ Fácil adição de novos tipos de notificações
- ✅ Melhor manutenibilidade
- ✅ Código mais testável

---

## 🎯 4. Strategy + Decorator Pattern

### **Onde foi aplicado:**
- `app/search/SearchStrategy.php` (interface strategy)
- `app/search/BuscarProdutosStrategy.php` (estratégia concreta)
- `app/search/ProdutoSearchInterface.php` (interface decorator)
- `app/search/ProdutoSearchBase.php` (busca base)
- `app/search/decorators/` (decorators de filtros)
- `app/repositories/ProductRepository.php` (execução)
- `app/controllers/ProductController.php` (integração)

### **Justificativa técnica:**
A combinação Strategy + Decorator foi implementada para criar um sistema de busca flexível:
- **Strategy define "o que buscar"**: Diferentes contextos de busca (produtos, lojas, etc.)
- **Decorator define "como filtrar"**: Critérios específicos aplicados dinamicamente
- **Eficiência**: Filtros aplicados a nível de SQL, não em memória
- **Flexibilidade**: Combinação dinâmica de filtros
- **Manutenibilidade**: Fácil adição de novos filtros

### **Arquitetura implementada:**

#### 🎯 **Strategy (BuscarProdutosStrategy)**
- **Responsabilidade**: Define o contexto de busca de produtos
- **Funcionalidade**: Aplica decorators baseado nos filtros recebidos
- **Integração**: Usa ProductRepository para executar a busca final

#### 🎨 **Decorators disponíveis:**
- **FiltroCategoriaDecorator**: Filtra por categoria de produto
- **FiltroPrecoMinDecorator**: Filtra por preço mínimo
- **FiltroPrecoMaxDecorator**: Filtra por preço máximo
- **FiltroMarcaDecorator**: Filtra por marca
- **FiltroCorDecorator**: Filtra por cor
- **FiltroTamanhoDecorator**: Filtra por tamanho
- **FiltroCondicaoDecorator**: Filtra por condição do produto
- **FiltroTextoDecorator**: Busca textual em nome, descrição e marca

### **Implementação:**
```php
// Strategy que usa Decorators
class BuscarProdutosStrategy implements SearchStrategy {
    public function search(): array {
        // 1. Busca base
        $search = new ProdutoSearchBase();
        
        // 2. Aplica decorators dinamicamente
        if (isset($this->filters['categoria'])) {
            $search = new FiltroCategoriaDecorator($search, $this->filters['categoria']);
        }
        if (isset($this->filters['preco_max'])) {
            $search = new FiltroPrecoMaxDecorator($search, $this->filters['preco_max']);
        }
        
        // 3. Executa no repository
        return $this->repo->executarBusca($search);
    }
}

// Decorator que modifica SQL
class FiltroCategoriaDecorator implements ProdutoSearchInterface {
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND c.slug = :categoria";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':categoria'] = $this->categoria;
        return $params;
    }
}
```

### **Benefícios obtidos:**
- ✅ Sistema de busca altamente flexível
- ✅ Filtros aplicados eficientemente no SQL
- ✅ Fácil adição de novos filtros
- ✅ Combinação dinâmica de critérios
- ✅ Código limpo e modular
- ✅ Melhor performance que filtros em memória

---

## 🔄 Integração dos Padrões

### **Como os padrões trabalham juntos:**

1. **Singleton + Repository**: A conexão única do banco é injetada nos repositórios
2. **Factory + Observer**: Produtos criados pelo Factory podem disparar eventos para observadores
3. **Strategy + Decorator**: Estratégias usam decorators para compor filtros dinamicamente
4. **Repository + Strategy**: Repositórios executam buscas compostas pelas estratégias
5. **Decorator + SQL**: Decorators modificam queries SQL para eficiência

### **Fluxo de busca:**
```
1. ProductController recebe filtros → 
2. BuscarProdutosStrategy aplica decorators → 
3. Decorators compõem SQL dinamicamente → 
4. ProductRepository executa busca otimizada
```

### **Fluxo de favoritos:**
```
1. ProductController recebe requisição → 
2. Extrai idProduto e idUsuario → 
3. ProductRepository executa operação SQL → 
4. Retorna resposta JSON
```

---

## 📊 Métricas de Qualidade

### **Antes da implementação:**
- ❌ Múltiplas conexões de banco
- ❌ Código acoplado para notificações
- ❌ Algoritmo único de busca
- ❌ Criação manual de produtos

### **Depois da implementação:**
- ✅ Conexão única e eficiente
- ✅ Sistema de notificações desacoplado
- ✅ Sistema de busca flexível com Strategy + Decorator
- ✅ Criação automática e tipada de produtos
- ✅ Favoritos integrados ao ProductController
- ✅ Repositórios com injeção de dependência
- ✅ Separação clara de responsabilidades

---

## 🚀 Benefícios Gerais Obtidos

### **Manutenibilidade:**
- Código mais organizado e modular
- Fácil adição de novas funcionalidades
- Responsabilidades bem definidas

### **Performance:**
- Conexão única com banco de dados
- Algoritmos de busca especializados
- Notificações em lote otimizadas

### **Extensibilidade:**
- Novos tipos de produtos facilmente adicionáveis
- Novos observadores podem ser criados
- Novas estratégias de busca podem ser implementadas

### **Testabilidade:**
- Cada padrão pode ser testado isoladamente
- Mocks e stubs mais fáceis de implementar
- Cobertura de testes mais eficiente

---

## 📝 Conclusão

A refatoração com padrões GoF no sistema de brechós resultou em:

- **Arquitetura mais robusta**: Strategy + Decorator para busca flexível
- **Separação de responsabilidades**: SQL apenas nos repositórios
- **Injeção de dependência**: PDO injetado via construtor
- **Código mais limpo**: Remoção da FavoritosController desnecessária
- **Melhor performance**: Filtros aplicados a nível de SQL
- **Maior flexibilidade**: Fácil adição de novos filtros e estratégias
- **Manutenibilidade aprimorada**: Código modular e testável

### **Principais melhorias:**

1. **FavoritosController removida** - Funcionalidade movida para ProductController
2. **Nova arquitetura de busca** - Strategy + Decorator para máxima flexibilidade
3. **Repositórios centralizados** - Todo SQL concentrado nos repositórios
4. **Injeção de dependência** - PDO injetado via construtor
5. **Filtros dinâmicos** - Decorators aplicados conforme necessidade

Todas as refatorações mantiveram a funcionalidade existente para o usuário final, melhorando significativamente a qualidade e organização do código.