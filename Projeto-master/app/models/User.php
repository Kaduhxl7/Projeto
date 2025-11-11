<?php
require_once __DIR__ . '/../config/DatabaseConnection.php';

class User {
    private $conn;
    private $table_name = "usuarios";

    // Campos básicos
    public $id;
    public $nome;
    public $sobrenome;
    public $usuario;
    public $celular;
    public $email;
    public $senha;
    public $foto_perfil;
    
    // Preferências
    public $quero_vender;
    public $quero_comprar;
    
    // Dados do brechó
    public $nome_brecho;
    public $localizacao_brecho;
    
    // Endereço de entrega
    public $cep;
    public $rua;
    public $numero;
    public $complemento;
    public $bairro;
    public $cidade;
    public $estado;

    public function __construct($db = null) {
        // Usar Singleton se não for fornecida conexão específica
        $this->conn = $db ?? DatabaseConnection::getInstance();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nome, email, senha) 
                  VALUES 
                  (:nome, :email, :senha)";
        
        $stmt = $this->conn->prepare($query);

        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);

        return $stmt->execute();
    }

    public function emailExists() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function usuarioExists() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }
    
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateLastLogin($id) {
        $query = "UPDATE " . $this->table_name . " SET updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($this->senha, $user['senha'])) {
            $this->id = $user['id'];
            $this->nome = $user['nome'];
            $this->usuario = $user['usuario'];
            return true;
        }
        return false;
    }
}
?>