<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroTextoDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $texto;
    
    public function __construct(ProdutoSearchInterface $busca, string $texto) {
        $this->busca = $busca;
        $this->texto = $texto;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND (p.nome LIKE :texto OR p.descricao LIKE :texto OR p.marca LIKE :texto)";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':texto'] = '%' . $this->texto . '%';
        return $params;
    }
}
?>