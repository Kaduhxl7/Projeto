<?php
/**
 * Padrão Singleton - Conexão com Banco de Dados
 * 
 * Garante que apenas uma instância da conexão PDO seja criada durante toda a execução,
 * evitando múltiplas conexões desnecessárias e melhorando a performance.
 * 
 * Justificativa: O Singleton é ideal para recursos compartilhados como conexões de banco,
 * pois evita overhead de múltiplas conexões e garante consistência.
 */

// Carregar configurações se não estiverem carregadas
if (!function_exists('env')) {
    require_once __DIR__ . '/config.php';
}

class DatabaseConnection {
    private static ?PDO $instance = null;
    private static array $config = [];
    
    /**
     * Construtor privado para prevenir instanciação direta
     */
    private function __construct() {}
    
    /**
     * Previne clonagem da instância
     */
    private function __clone() {}
    
    /**
     * Previne deserialização da instância
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
    /**
     * Obtém a instância única da conexão PDO
     * 
     * @return PDO Instância única da conexão
     * @throws Exception Se não conseguir conectar
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::initializeConnection();
        }
        
        return self::$instance;
    }
    
    /**
     * Inicializa a conexão com o banco de dados
     * 
     * @throws Exception Se não conseguir conectar
     */
    private static function initializeConnection(): void {
        self::loadConfig();
        
        $passwords = ['', 'root', '1234', 'password'];
        
        foreach ($passwords as $password) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . self::$config['host'] . ";dbname=" . self::$config['db_name'] . ";charset=" . self::$config['charset'],
                    self::$config['username'],
                    $password
                );
                
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Se chegou aqui, conexão funcionou
                self::$config['password'] = $password;
                return;
                
            } catch(PDOException $e) {
                // Continua tentando próxima senha
                continue;
            }
        }
        
        // Se ainda não conectou, tenta criar o banco
        self::createDatabaseIfNotExists();
    }
    
    /**
     * Carrega as configurações do banco
     */
    private static function loadConfig(): void {
        self::$config = [
            'host' => env('DB_HOST', 'localhost'),
            'db_name' => env('DB_NAME', 'dresscode'),
            'username' => env('DB_USER', 'root'),
            'charset' => 'utf8mb4'
        ];
    }
    
    /**
     * Cria o banco de dados se não existir
     * 
     * @throws Exception Se não conseguir criar o banco
     */
    private static function createDatabaseIfNotExists(): void {
        $passwords = ['', 'root', '1234', 'password'];
        
        foreach ($passwords as $password) {
            try {
                // Conecta sem especificar banco
                $tempConn = new PDO(
                    "mysql:host=" . self::$config['host'] . ";charset=" . self::$config['charset'],
                    self::$config['username'],
                    $password
                );
                $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Cria o banco
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS " . self::$config['db_name'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Conecta ao banco criado
                self::$instance = new PDO(
                    "mysql:host=" . self::$config['host'] . ";dbname=" . self::$config['db_name'] . ";charset=" . self::$config['charset'],
                    self::$config['username'],
                    $password
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                self::$config['password'] = $password;
                return;
                
            } catch(PDOException $e) {
                continue;
            }
        }
        
        throw new Exception("❌ Erro: Não foi possível conectar ao MySQL. Verifique se o Laragon está rodando.");
    }
    
    /**
     * Testa a conexão
     * 
     * @return bool True se a conexão estiver ativa
     */
    public static function testConnection(): bool {
        try {
            $pdo = self::getInstance();
            $pdo->query('SELECT 1');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Obtém informações da conexão para debug
     * 
     * @return array Informações da conexão
     */
    public static function getConnectionInfo(): array {
        return [
            'host' => self::$config['host'] ?? 'N/A',
            'database' => self::$config['db_name'] ?? 'N/A',
            'username' => self::$config['username'] ?? 'N/A',
            'charset' => self::$config['charset'] ?? 'N/A',
            'connected' => self::$instance !== null
        ];
    }
}
?>