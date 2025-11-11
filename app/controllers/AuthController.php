<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/SecurityController.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Campos básicos
            $this->user->nome = trim($_POST["nome"]);
            $this->user->sobrenome = trim($_POST["sobrenome"]);
            $this->user->usuario = trim($_POST["usuario"]);
            $this->user->celular = trim($_POST["celular"]);
            $this->user->email = trim($_POST["email"]);
            $this->user->senha = $_POST["senha"];
            
            // Tipo de usuário
            $tipo_usuario = $_POST['tipo_usuario'] ?? '';
            if (!in_array($tipo_usuario, ['comprador', 'vendedor'])) {
                return ["status" => "error", "message" => "Você deve selecionar o tipo de conta."];
            }
            
            // Definir preferências baseado no tipo
            $this->user->quero_vender = ($tipo_usuario === 'vendedor') ? 1 : 0;
            $this->user->quero_comprar = ($tipo_usuario === 'comprador') ? 1 : 0;
            
            // Campos condicionais do brechó
            if ($tipo_usuario === 'vendedor') {
                $this->user->nome_brecho = trim($_POST["nome_brecho"] ?? '');
                $this->user->localizacao_brecho = trim($_POST["localizacao_brecho"] ?? '');
            }
            
            // Campos condicionais do endereço
            if ($tipo_usuario === 'comprador') {
                $this->user->cep = trim($_POST["cep"] ?? '');
                $this->user->rua = trim($_POST["rua"] ?? '');
                $this->user->numero = trim($_POST["numero"] ?? '');
                $this->user->complemento = trim($_POST["complemento"] ?? '');
                $this->user->bairro = trim($_POST["bairro"] ?? '');
                $this->user->cidade = trim($_POST["cidade"] ?? '');
                $this->user->estado = trim($_POST["estado"] ?? '');
            }
            
            // Upload da foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $this->uploadFoto($_FILES['foto']);
                if ($foto) {
                    $this->user->foto_perfil = $foto;
                }
            }

            // Validações
            if (!filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
                return ["status" => "error", "message" => "E-mail inválido."];
            }
            


            if ($this->user->emailExists()) {
                return ["status" => "error", "message" => "Este e-mail já está cadastrado."];
            }
            
            if ($this->user->usuarioExists()) {
                return ["status" => "error", "message" => "Este nome de usuário já está em uso."];
            }

            if ($this->user->create()) {
                return ["status" => "success", "message" => "Cadastro realizado com sucesso!"];
            } else {
                return ["status" => "error", "message" => "Erro ao cadastrar. Tente novamente."];
            }
        }
        return null;
    }
    
    private function uploadFoto($arquivo) {
        $uploadDir = 'assets/uploads/perfil/';
        
        // Criar diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extensao, $extensoesPermitidas)) {
            return false;
        }
        
        if ($arquivo['size'] > 5 * 1024 * 1024) { // 5MB
            return false;
        }
        
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;
        
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            return $caminhoCompleto;
        }
        
        return false;
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->user->email = trim($_POST["email"]);
            $this->user->senha = $_POST["senha"];
            
            $security = new SecurityController();

            if ($this->user->login()) {
                $_SESSION["user_id"] = $this->user->id;
                $_SESSION["user_email"] = $this->user->email;
                
                // Log de login bem-sucedido
                $security->logLoginAttempt($this->user->email, true);
                $security->logAction($this->user->id, 'LOGIN_SUCESSO');
                
                return ["status" => "success", "message" => "Login realizado com sucesso!"];
            } else {
                // Log de tentativa de login falhada
                $security->logLoginAttempt($this->user->email, false);
                
                return ["status" => "error", "message" => "E-mail ou senha inválidos."];
            }
        }
        return null;
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/public/login.php');
        exit();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'] ?? null
            ];
        }
        return null;
    }
}
?>