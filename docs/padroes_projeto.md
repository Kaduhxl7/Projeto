# Padrões de Projeto GoF Implementados

Este documento descreve os padrões de projeto (Design Patterns) implementados no sistema de brechós/marketplace, seguindo os padrões GoF (Gang of Four).

## 📋 Resumo dos Padrões Implementados

| Padrão | Categoria | Localização | Justificativa |
|--------|-----------|-------------|---------------|
| **Singleton** | Criacional | `app/config/DatabaseConnection.php` | Garante uma única instância de conexão com banco |
| **Factory Method** | Criacional | `app/factories/ProductFactory.php` | Cria diferentes tipos de produtos baseados na condição |
| **Observer** | Comportamental | `app/observers/` | Desacopla sistema de notificações dos controladores |
| **Strategy** | Comportamental | `app/strategies/` | Permite diferentes algoritmos de busca intercambiáveis |

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

## 🎯 4. Strategy Pattern

### **Onde foi aplicado:**
- `app/strategies/SearchStrategy.php` (interface)
- `app/strategies/SearchByLocationStrategy.php` (estratégia concreta)
- `app/strategies/SearchByCategoryStrategy.php` (estratégia concreta)
- `app/strategies/SearchByPriceStrategy.php` (estratégia concreta)
- `app/strategies/SearchContext.php` (contexto)
- `app/controllers/BuscaController.php` (integração)
- `app/controllers/ProductController.php` (integração)

### **Justificativa técnica:**
O Strategy foi implementado para diferentes algoritmos de busca porque:
- **Intercambiabilidade**: Permite trocar algoritmos de busca dinamicamente
- **Otimização**: Cada estratégia é otimizada para seu caso específico
- **Manutenibilidade**: Facilita modificação e adição de novos algoritmos
- **Separação de responsabilidades**: Cada estratégia foca em um tipo de busca

### **Estratégias implementadas:**

#### 🌍 **SearchByLocationStrategy**
- **Uso**: Busca por proximidade geográfica
- **Algoritmo**: Fórmula Haversine para cálculo de distâncias
- **Otimização**: Ordenação por distância, filtro por raio

#### 📂 **SearchByCategoryStrategy**
- **Uso**: Busca focada em categorias de produtos
- **Algoritmo**: Filtros avançados por atributos (cor, tamanho, marca, condição)
- **Otimização**: Ordenação por relevância, prioriza produtos promocionais

#### 💰 **SearchByPriceStrategy**
- **Uso**: Busca otimizada para faixas de preço
- **Algoritmo**: Faixas predefinidas, cálculo de descontos
- **Otimização**: Prioriza ofertas e melhor custo-benefício

### **Implementação:**
```php
// Context que gerencia as estratégias
class SearchContext {
    private ?SearchStrategy $strategy = null;
    
    public function determineStrategy(array $filters): SearchStrategy {
        if (!empty($filters['latitude'])) return new SearchByLocationStrategy();
        if (!empty($filters['preco_min'])) return new SearchByPriceStrategy();
        return new SearchByCategoryStrategy(); // Padrão
    }
    
    public function smartSearch(array $filters): array {
        $this->strategy = $this->determineStrategy($filters);
        return $this->strategy->search($db, $filters);
    }
}
```

### **Benefícios obtidos:**
- ✅ Algoritmos de busca especializados
- ✅ Melhor performance para cada tipo de busca
- ✅ Fácil adição de novas estratégias
- ✅ Seleção automática da melhor estratégia

---

## 🔄 Integração dos Padrões

### **Como os padrões trabalham juntos:**

1. **Singleton + Factory**: A conexão única do banco é usada pelo Factory para criar produtos
2. **Factory + Observer**: Produtos criados pelo Factory podem disparar eventos para observadores
3. **Observer + Strategy**: Notificações podem usar diferentes estratégias de entrega
4. **Strategy + Singleton**: Estratégias de busca usam a conexão única do banco

### **Fluxo de exemplo:**
```
1. ProductFactory cria produto → 
2. ProductPublisher notifica observadores → 
3. NotificationObserver cria notificações → 
4. SearchContext usa estratégia otimizada para buscar produtos
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
- ✅ Múltiplas estratégias de busca otimizadas
- ✅ Criação automática e tipada de produtos

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

A implementação dos padrões GoF no sistema de brechós resultou em:

- **Código mais limpo e organizad**: Seguindo princípios SOLID
- **Melhor performance**: Otimizações específicas para cada caso de uso
- **Maior flexibilidade**: Facilita mudanças e extensões futuras
- **Manutenibilidade aprimorada**: Código mais fácil de entender e modificar

Todos os padrões foram aplicados de forma justificada e adequada ao domínio do sistema, melhorando significativamente a qualidade do código sem quebrar funcionalidades existentes.