<?php
/**
 * Padrão Strategy - Contexto de Busca
 * 
 * Classe responsável por gerenciar e executar diferentes estratégias de busca.
 * Permite trocar algoritmos de busca dinamicamente baseado nos filtros fornecidos.
 * 
 * Justificativa: O Strategy permite que diferentes algoritmos de busca sejam
 * intercambiáveis, facilitando manutenção e adição de novas estratégias sem
 * modificar o código existente.
 */

require_once __DIR__ . '/SearchStrategy.php';
require_once __DIR__ . '/SearchByLocationStrategy.php';
require_once __DIR__ . '/SearchByCategoryStrategy.php';
require_once __DIR__ . '/SearchByPriceStrategy.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';

class SearchContext {
    private ?SearchStrategy $strategy = null;
    private PDO $db;
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance();
    }
    
    /**
     * Define a estratégia de busca
     */
    public function setStrategy(SearchStrategy $strategy): void {
        $this->strategy = $strategy;
    }
    
    /**
     * Executa a busca usando a estratégia atual
     */
    public function executeSearch(array $filters): array {
        if ($this->strategy === null) {
            $this->strategy = $this->determineStrategy($filters);
        }
        
        return $this->strategy->search($this->db, $filters);
    }
    
    /**
     * Conta resultados usando a estratégia atual
     */
    public function countResults(array $filters): int {
        if ($this->strategy === null) {
            $this->strategy = $this->determineStrategy($filters);
        }
        
        return $this->strategy->count($this->db, $filters);
    }
    
    /**
     * Determina automaticamente a melhor estratégia baseada nos filtros
     */
    public function determineStrategy(array $filters): SearchStrategy {
        // Prioridade 1: Busca por localização (se tem coordenadas)
        if (!empty($filters['latitude']) && !empty($filters['longitude'])) {
            return new SearchByLocationStrategy();
        }
        
        // Prioridade 2: Busca por preço (se tem filtros de preço específicos)
        if (!empty($filters['preco_min']) || !empty($filters['preco_max']) || !empty($filters['faixa_preco']) || !empty($filters['apenas_ofertas'])) {
            return new SearchByPriceStrategy();
        }
        
        // Prioridade 3: Busca por categoria (se tem categoria específica)
        if (!empty($filters['categoria'])) {
            return new SearchByCategoryStrategy();
        }
        
        // Padrão: Busca por categoria (mais versátil)
        return new SearchByCategoryStrategy();
    }
    
    /**
     * Executa busca com determinação automática de estratégia
     */
    public function smartSearch(array $filters): array {
        $this->strategy = $this->determineStrategy($filters);
        return $this->executeSearch($filters);
    }
    
    /**
     * Conta resultados com determinação automática de estratégia
     */
    public function smartCount(array $filters): int {
        $this->strategy = $this->determineStrategy($filters);
        return $this->countResults($filters);
    }
    
    /**
     * Obtém informações sobre a estratégia atual
     */
    public function getCurrentStrategyInfo(): array {
        if ($this->strategy === null) {
            return ['name' => 'Nenhuma estratégia definida', 'class' => null];
        }
        
        return [
            'name' => $this->strategy->getName(),
            'class' => get_class($this->strategy)
        ];
    }
    
    /**
     * Lista todas as estratégias disponíveis
     */
    public static function getAvailableStrategies(): array {
        return [
            'location' => [
                'name' => 'Busca por Localização',
                'class' => SearchByLocationStrategy::class,
                'description' => 'Busca baseada em proximidade geográfica'
            ],
            'category' => [
                'name' => 'Busca por Categoria',
                'class' => SearchByCategoryStrategy::class,
                'description' => 'Busca focada em categorias de produtos'
            ],
            'price' => [
                'name' => 'Busca por Preço',
                'class' => SearchByPriceStrategy::class,
                'description' => 'Busca otimizada para faixas de preço'
            ]
        ];
    }
    
    /**
     * Cria uma estratégia específica por nome
     */
    public function createStrategy(string $strategyName): ?SearchStrategy {
        $strategies = self::getAvailableStrategies();
        
        if (!isset($strategies[$strategyName])) {
            return null;
        }
        
        $className = $strategies[$strategyName]['class'];
        return new $className();
    }
    
    /**
     * Executa busca com estratégia específica
     */
    public function searchWithStrategy(string $strategyName, array $filters): array {
        $strategy = $this->createStrategy($strategyName);
        
        if ($strategy === null) {
            throw new InvalidArgumentException("Estratégia '{$strategyName}' não encontrada");
        }
        
        $this->setStrategy($strategy);
        return $this->executeSearch($filters);
    }
    
    /**
     * Valida filtros para uma estratégia específica
     */
    public function validateFiltersForStrategy(string $strategyName, array $filters): array {
        $errors = [];
        
        switch ($strategyName) {
            case 'location':
                if (empty($filters['latitude']) || empty($filters['longitude'])) {
                    $errors[] = 'Latitude e longitude são obrigatórias para busca por localização';
                }
                break;
            case 'price':
                if (empty($filters['preco_min']) && empty($filters['preco_max']) && empty($filters['faixa_preco'])) {
                    $errors[] = 'Pelo menos um filtro de preço é necessário para busca por preço';
                }
                break;
            case 'category':
                // Categoria é opcional, mas se fornecida deve ser válida
                break;
        }
        
        return $errors;
    }
}
?>