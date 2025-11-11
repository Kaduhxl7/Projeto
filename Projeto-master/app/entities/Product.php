<?php

class Product {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $tamanho;
    public $cor;
    public $marca;
    public $condicao;
    public $categoria_id;
    public $imagem;
    public $status;
    public $visualizacoes;
    public $created_at;
    public $updated_at;
    
    // Propriedades específicas dos tipos de produto
    private $desconto = 0;
    private $garantia = false;
    private $prioridade = 2;
    private $promocional = false;
    private $vintage = false;

    public function __construct($id = null, $nome = null, $descricao = null, $preco = null, 
                               $tamanho = null, $cor = null, $marca = null, $condicao = 'Seminovo',
                               $categoria_id = null, $status = 'Ativo', $visualizacoes = 0) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->tamanho = $tamanho;
        $this->cor = $cor;
        $this->marca = $marca;
        $this->condicao = $condicao;
        $this->categoria_id = $categoria_id;
        $this->status = $status;
        $this->visualizacoes = $visualizacoes;
    }
    
    /**
     * Define os dados do produto
     */
    public function setData(array $data): void {
        $this->id = $data['id'] ?? $this->id;
        $this->nome = $data['nome'] ?? $this->nome;
        $this->descricao = $data['descricao'] ?? $this->descricao;
        $this->preco = $data['preco'] ?? $this->preco;
        $this->tamanho = $data['tamanho'] ?? $this->tamanho;
        $this->cor = $data['cor'] ?? $this->cor;
        $this->marca = $data['marca'] ?? $this->marca;
        $this->condicao = $data['condicao'] ?? $this->condicao;
        $this->categoria_id = $data['categoria_id'] ?? $this->categoria_id;
        $this->imagem = $data['imagem'] ?? $this->imagem;
        $this->status = $data['status'] ?? $this->status;
        $this->visualizacoes = $data['visualizacoes'] ?? $this->visualizacoes;
        $this->created_at = $data['created_at'] ?? $this->created_at;
        $this->updated_at = $data['updated_at'] ?? $this->updated_at;
    }
    
    // Getters e Setters para propriedades específicas
    public function setCondicao(string $condicao): void {
        $this->condicao = $condicao;
    }
    
    public function setDesconto(float $desconto): void {
        $this->desconto = $desconto;
    }
    
    public function getDesconto(): float {
        return $this->desconto;
    }
    
    public function setGarantia(bool $garantia): void {
        $this->garantia = $garantia;
    }
    
    public function hasGarantia(): bool {
        return $this->garantia;
    }
    
    public function setPrioridade(int $prioridade): void {
        $this->prioridade = $prioridade;
    }
    
    public function getPrioridade(): int {
        return $this->prioridade;
    }
    
    public function setPromocional(bool $promocional): void {
        $this->promocional = $promocional;
    }
    
    public function isPromocional(): bool {
        return $this->promocional;
    }
    
    public function setVintage(bool $vintage): void {
        $this->vintage = $vintage;
    }
    
    public function isVintage(): bool {
        return $this->vintage;
    }
    
    /**
     * Calcula o preço com desconto
     */
    public function getPrecoComDesconto(): float {
        if ($this->desconto > 0) {
            return $this->preco * (1 - $this->desconto / 100);
        }
        return $this->preco;
    }
    
    /**
     * Obtém informações do tipo do produto
     */
    public function getTipoInfo(): array {
        return [
            'condicao' => $this->condicao,
            'desconto' => $this->desconto,
            'garantia' => $this->garantia,
            'prioridade' => $this->prioridade,
            'promocional' => $this->promocional,
            'vintage' => $this->vintage
        ];
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'preco_com_desconto' => $this->getPrecoComDesconto(),
            'tamanho' => $this->tamanho,
            'cor' => $this->cor,
            'marca' => $this->marca,
            'condicao' => $this->condicao,
            'categoria_id' => $this->categoria_id,
            'imagem' => $this->imagem,
            'status' => $this->status,
            'visualizacoes' => $this->visualizacoes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tipo_info' => $this->getTipoInfo()
        ];
    }
}
?>