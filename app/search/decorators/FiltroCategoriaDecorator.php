<?php
require_once __DIR__ . '/../ProdutoSearchInterface.php';

class FiltroCategoriaDecorator implements ProdutoSearchInterface {
    private ProdutoSearchInterface $busca;
    private string $categoria;
    
    public function __construct(ProdutoSearchInterface $busca, string $categoria) {
        $this->busca = $busca;
        $this->categoria = $categoria;
    }
    
    public function getSQL(): string {
        return $this->busca->getSQL() . " AND c.slug = :categoria";
    }
    
    public function getParams(): array {
        $params = $this->busca->getParams();
        $params[':categoria'] = $this->categoria;
        return $params;
    }
}
?>