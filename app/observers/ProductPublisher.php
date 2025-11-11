<?php
/**
 * Padrão Observer - Publisher de Produtos
 * 
 * Responsável por notificar observadores sobre eventos relacionados a produtos
 * (novos produtos, promoções, atualizações, etc.).
 * 
 * Justificativa: O Observer desacopla o sistema de notificações dos controladores,
 * permitindo que múltiplos sistemas sejam notificados automaticamente sem
 * modificar o código principal.
 */

require_once __DIR__ . '/Subject.php';
require_once __DIR__ . '/Observer.php';

class ProductPublisher implements Subject {
    private array $observers = [];
    private static ?ProductPublisher $instance = null;
    
    /**
     * Singleton para garantir uma única instância do publisher
     */
    public static function getInstance(): ProductPublisher {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {}
    
    /**
     * Adiciona um observador à lista
     */
    public function attach(Observer $observer): void {
        $observerClass = get_class($observer);
        
        // Evita duplicatas
        if (!isset($this->observers[$observerClass])) {
            $this->observers[$observerClass] = $observer;
        }
    }
    
    /**
     * Remove um observador da lista
     */
    public function detach(Observer $observer): void {
        $observerClass = get_class($observer);
        unset($this->observers[$observerClass]);
    }
    
    /**
     * Notifica todos os observadores sobre um evento
     */
    public function notify(string $eventType, array $data): void {
        foreach ($this->observers as $observer) {
            try {
                $observer->update($eventType, $data);
            } catch (Exception $e) {
                // Log do erro mas continua notificando outros observadores
                error_log("Erro ao notificar observador: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Notifica sobre novo produto
     */
    public function notifyNewProduct(int $productId, string $productName, ?int $brechoId = null): void {
        $this->notify('new_product', [
            'product_id' => $productId,
            'product_name' => $productName,
            'brecho_id' => $brechoId,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Notifica sobre promoção
     */
    public function notifyPromotion(string $title, string $description, ?int $brechoId = null, ?string $brechoName = null): void {
        $this->notify('promotion', [
            'title' => $title,
            'description' => $description,
            'brecho_id' => $brechoId,
            'brecho_name' => $brechoName,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Notifica sobre atualização de brechó
     */
    public function notifyStoreUpdate(int $brechoId, string $brechoName): void {
        $this->notify('store_update', [
            'brecho_id' => $brechoId,
            'brecho_name' => $brechoName,
            'timestamp' => time()
        ]);
    }
    
    /**
     * Notifica sobre produto em promoção
     */
    public function notifyProductPromotion(int $productId, string $productName, float $oldPrice, float $newPrice): void {
        $this->notify('product_promotion', [
            'product_id' => $productId,
            'product_name' => $productName,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'discount_percentage' => round((($oldPrice - $newPrice) / $oldPrice) * 100, 2),
            'timestamp' => time()
        ]);
    }
    
    /**
     * Obtém lista de observadores registrados
     */
    public function getObservers(): array {
        return array_keys($this->observers);
    }
    
    /**
     * Verifica se um tipo de observador está registrado
     */
    public function hasObserver(string $observerClass): bool {
        return isset($this->observers[$observerClass]);
    }
}
?>