<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

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
            $this->user->email = trim($_POST["email"]);
            $this->user->senha = $_POST["senha"];

            if (!filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
                return ["status" => "error", "message" => "E-mail inválido."];
            }

            if ($this->user->emailExists()) {
                return ["status" => "error", "message" => "Este e-mail já está cadastrado."];
            }

            if ($this->user->create()) {
                return ["status" => "success", "message" => "Cadastro realizado com sucesso!"];
            } else {
                return ["status" => "error", "message" => "Erro ao cadastrar. Tente novamente."];
            }
        }
        return null;
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->user->email = trim($_POST["email"]);
            $this->user->senha = $_POST["senha"];

            if ($this->user->login()) {
                $_SESSION["user_id"] = $this->user->id;
                $_SESSION["user_email"] = $this->user->email;
                return ["status" => "success", "message" => "Login realizado com sucesso!"];
            } else {
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