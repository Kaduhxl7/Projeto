<?php
/**
 * Padrão Strategy - Interface de Estratégia de Busca
 * 
 * Define o contrato para diferentes algoritmos de busca.
 * Permite trocar algoritmos de busca dinamicamente sem alterar o código cliente.
 */

interface SearchStrategy {
    /**
     * Executa a busca com base na estratégia implementada
     * 
     * @param PDO $db Conexão com banco de dados
     * @param array $filters Filtros de busca
     * @return array Resultados da busca
     */
    public function search(PDO $db, array $filters): array;
    
    /**
     * Conta o total de resultados para paginação
     * 
     * @param PDO $db Conexão com banco de dados
     * @param array $filters Filtros de busca
     * @return int Total de resultados
     */
    public function count(PDO $db, array $filters): int;
    
    /**
     * Obtém o nome da estratégia
     * 
     * @return string Nome da estratégia
     */
    public function getName(): string;
}
?>