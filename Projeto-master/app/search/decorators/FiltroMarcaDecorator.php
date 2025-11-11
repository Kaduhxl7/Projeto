<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroMarcaDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $marca;
    
    public function __construct(ProdutoSearchInterface $busca, string $marca) {
        $this->busca = $busca;
        $this->marca = $marca;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND p.marca LIKE :marca";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':marca'] = '%' . $this->marca . '%';
        return $params;
    }
}
?>