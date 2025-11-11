<?php
/**
 * Interface para Decorators de busca
 * Define "como filtrar" - cada decorator adiciona critérios específicos
 */
interface ProdutoSearchInterface {
    /**
     * Retorna a query SQL construída
     * @return string Query SQL
     */
    public function getSQL(): string;
    
    /**
     * Retorna os parâmetros para a query
     * @return array Parâmetros da query
     */
    public function getParams(): array;
}
?>