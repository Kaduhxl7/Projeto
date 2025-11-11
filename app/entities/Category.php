<?php

class Category {
    public $id;
    public $nome;
    public $slug;
    public $descricao;
    public $ativo;

    public function __construct($id = null, $nome = null, $slug = null, $descricao = null, $ativo = true) {
        $this->id = $id;
        $this->nome = $nome;
        $this->slug = $slug;
        $this->descricao = $descricao;
        $this->ativo = $ativo;
    }
}
?>