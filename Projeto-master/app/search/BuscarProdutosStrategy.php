<?php
require_once __DIR__ . '/SearchStrategy.php';
require_once __DIR__ . '/ProdutoSearchBase.php';
require_once __DIR__ . '/decorators/FiltroCategoriaDecorator.php';
require_once __DIR__ . '/decorators/FiltroPrecoMaxDecorator.php';
require_once __DIR__ . '/decorators/FiltroPrecoMinDecorator.php';
require_once __DIR__ . '/decorators/FiltroMarcaDecorator.php';
require_once __DIR__ . '/decorators/FiltroCorDecorator.php';
require_once __DIR__ . '/decorators/FiltroTamanhoDecorator.php';
require_once __DIR__ . '/decorators/FiltroCondicaoDecorator.php';
require_once __DIR__ . '/decorators/FiltroTextoDecorator.php';

class BuscarProdutosStrategy implements SearchStrategy {
    private $repo;
    private array $filters;

    public function __construct($repo, array $filters) {
        $this->repo = $repo;
        $this->filters = $filters;
    }

    public function search(): array {
        // 1. Começa com a busca base
        $search = new ProdutoSearchBase();

        // 2. Aplica Decorators com base nos filtros recebidos
        if (isset($this->filters['categoria'])) {
            $search = new FiltroCategoriaDecorator($search, $this->filters['categoria']);
        }
        if (isset($this->filters['preco_max'])) {
            $search = new FiltroPrecoMaxDecorator($search, $this->filters['preco_max']);
        }
        if (isset($this->filters['preco_min'])) {
            $search = new FiltroPrecoMinDecorator($search, $this->filters['preco_min']);
        }
        if (isset($this->filters['marca'])) {
            $search = new FiltroMarcaDecorator($search, $this->filters['marca']);
        }
        if (isset($this->filters['cor'])) {
            $search = new FiltroCorDecorator($search, $this->filters['cor']);
        }
        if (isset($this->filters['tamanho'])) {
            $search = new FiltroTamanhoDecorator($search, $this->filters['tamanho']);
        }
        if (isset($this->filters['condicao'])) {
            $search = new FiltroCondicaoDecorator($search, $this->filters['condicao']);
        }
        if (isset($this->filters['search'])) {
            $search = new FiltroTextoDecorator($search, $this->filters['search']);
        }

        // 3. Executa a busca composta no repository
        return $this->repo->executarBusca($search);
    }
}
?>