<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroTamanhoDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $tamanho;
    
    public function __construct(ProdutoSearchInterface $busca, string $tamanho) {
        $this->busca = $busca;
        $this->tamanho = $tamanho;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.tamanho = :tamanho";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':tamanho'] = $this->tamanho;
        return $params;
    }
}
?>