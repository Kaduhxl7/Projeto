<?php
/**
 * Interface Strategy para busca de produtos
 * Define "o que buscar" - cada estratégia representa um contexto de busca diferente
 */
interface SearchStrategy {
    /**
     * Executa a busca e retorna os resultados
     * @return array Resultados da busca
     */
    public function search(): array;
}
?>