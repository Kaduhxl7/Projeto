<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'dresscode';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Tentar diferentes senhas comuns do Laragon
        $passwords = ['', 'root', '1234', 'password'];
        
        foreach ($passwords as $pwd) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset,
                    $this->username,
                    $pwd
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Se chegou aqui, conexão funcionou
                $this->password = $pwd; // Salva a senha que funcionou
                break;
            } catch(PDOException $e) {
                // Continua tentando próxima senha
                continue;
            }
        }
        
        // Se ainda não conectou, tenta criar o banco
        if (!$this->conn) {
            $this->createDatabase();
        }
        
        return $this->conn;
    }
    
    private function createDatabase() {
        $passwords = ['', 'root', '1234', 'password'];
        
        foreach ($passwords as $pwd) {
            try {
                // Conecta sem especificar banco
                $tempConn = new PDO(
                    "mysql:host=" . $this->host . ";charset=" . $this->charset,
                    $this->username,
                    $pwd
                );
                $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Cria o banco
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Conecta ao banco criado
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset,
                    $this->username,
                    $pwd
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->password = $pwd;
                break;
            } catch(PDOException $e) {
                continue;
            }
        }
        
        if (!$this->conn) {
            die("❌ Erro: Não foi possível conectar ao MySQL. Verifique se o Laragon está rodando e tente as senhas: vazia, 'root', '1234' ou 'password'");
        }
    }
}
?>