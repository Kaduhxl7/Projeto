<?php
/**
 * Padrão Factory Method - Criação de Produtos
 * 
 * Responsável por criar diferentes tipos de produtos baseados na condição,
 * encapsulando a lógica de criação e permitindo extensibilidade futura.
 * 
 * Justificativa: O Factory Method é ideal quando temos diferentes tipos de objetos
 * que compartilham uma interface comum, mas têm comportamentos específicos.
 * Facilita a manutenção e adição de novos tipos de produtos.
 */

require_once __DIR__ . '/../entities/Product.php';

abstract class ProductFactory {
    
    /**
     * Método factory principal - cria produto baseado na condição
     * 
     * @param array $data Dados do produto
     * @return Product Instância do produto criado
     * @throws InvalidArgumentException Se a condição for inválida
     */
    public static function createProduct(array $data): Product {
        $condicao = $data['condicao'] ?? 'usado';
        
        switch (strtolower($condicao)) {
            case 'novo':
                return self::createNewProduct($data);
            case 'usado':
                return self::createUsedProduct($data);
            case 'promocional':
                return self::createPromotionalProduct($data);
            case 'vintage':
                return self::createVintageProduct($data);
            default:
                throw new InvalidArgumentException("Condição de produto inválida: {$condicao}");
        }
    }
    
    /**
     * Cria produto novo
     * 
     * @param array $data Dados do produto
     * @return Product
     */
    private static function createNewProduct(array $data): Product {
        $product = new Product();
        $product->setData($data);
        $product->setCondicao('novo');
        $product->setDesconto(0); // Produtos novos não têm desconto padrão
        $product->setGarantia(true); // Produtos novos têm garantia
        $product->setPrioridade(1); // Alta prioridade na busca
        
        return $product;
    }
    
    /**
     * Cria produto usado
     * 
     * @param array $data Dados do produto
     * @return Product
     */
    private static function createUsedProduct(array $data): Product {
        $product = new Product();
        $product->setData($data);
        $product->setCondicao('usado');
        $product->setDesconto(10); // Desconto padrão para usados
        $product->setGarantia(false); // Produtos usados sem garantia
        $product->setPrioridade(2); // Prioridade média
        
        return $product;
    }
    
    /**
     * Cria produto promocional
     * 
     * @param array $data Dados do produto
     * @return Product
     */
    private static function createPromotionalProduct(array $data): Product {
        $product = new Product();
        $product->setData($data);
        $product->setCondicao('promocional');
        $product->setDesconto($data['desconto'] ?? 20); // Desconto promocional
        $product->setGarantia(true); // Produtos promocionais mantêm garantia
        $product->setPrioridade(0); // Máxima prioridade (aparece primeiro)
        $product->setPromocional(true);
        
        return $product;
    }
    
    /**
     * Cria produto vintage
     * 
     * @param array $data Dados do produto
     * @return Product
     */
    private static function createVintageProduct(array $data): Product {
        $product = new Product();
        $product->setData($data);
        $product->setCondicao('vintage');
        $product->setDesconto(0); // Produtos vintage mantêm preço
        $product->setGarantia(false); // Sem garantia por serem antigos
        $product->setPrioridade(1); // Alta prioridade (são especiais)
        $product->setVintage(true);
        
        return $product;
    }
    
    /**
     * Cria produto a partir de dados do banco
     * 
     * @param array $dbData Dados vindos do banco de dados
     * @return Product
     */
    public static function createFromDatabase(array $dbData): Product {
        // Mapear campos do banco para o formato esperado
        $productData = [
            'id' => $dbData['id'] ?? null,
            'nome' => $dbData['nome'] ?? '',
            'descricao' => $dbData['descricao'] ?? '',
            'preco' => $dbData['preco'] ?? 0,
            'tamanho' => $dbData['tamanho'] ?? '',
            'cor' => $dbData['cor'] ?? '',
            'marca' => $dbData['marca'] ?? '',
            'condicao' => $dbData['condicao'] ?? 'usado',
            'categoria_id' => $dbData['categoria_id'] ?? null,
            'imagem' => $dbData['imagem'] ?? '',
            'status' => $dbData['status'] ?? 'Ativo',
            'visualizacoes' => $dbData['visualizacoes'] ?? 0,
            'created_at' => $dbData['created_at'] ?? null,
            'updated_at' => $dbData['updated_at'] ?? null
        ];
        
        return self::createProduct($productData);
    }
    
    /**
     * Cria múltiplos produtos a partir de array de dados
     * 
     * @param array $productsData Array de dados de produtos
     * @return array Array de objetos Product
     */
    public static function createMultipleProducts(array $productsData): array {
        $products = [];
        
        foreach ($productsData as $productData) {
            try {
                $products[] = self::createProduct($productData);
            } catch (InvalidArgumentException $e) {
                // Log do erro e continua com próximo produto
                error_log("Erro ao criar produto: " . $e->getMessage());
                continue;
            }
        }
        
        return $products;
    }
    
    /**
     * Obtém tipos de produtos disponíveis
     * 
     * @return array Lista de condições disponíveis
     */
    public static function getAvailableProductTypes(): array {
        return [
            'novo' => 'Produto Novo',
            'usado' => 'Produto Usado',
            'promocional' => 'Produto Promocional',
            'vintage' => 'Produto Vintage'
        ];
    }
    
    /**
     * Valida se um tipo de produto é válido
     * 
     * @param string $type Tipo do produto
     * @return bool
     */
    public static function isValidProductType(string $type): bool {
        return array_key_exists(strtolower($type), self::getAvailableProductTypes());
    }
}
?>