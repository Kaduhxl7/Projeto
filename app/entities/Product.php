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
    public $status;
    public $visualizacoes;
    public $created_at;
    public $updated_at;

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
}
?>