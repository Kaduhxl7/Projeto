<?php
/**
 * Padrão Observer - Interface Subject
 * 
 * Define o contrato para objetos que podem ser observados.
 * Gerencia a lista de observadores e os notifica sobre mudanças.
 */

require_once __DIR__ . '/Observer.php';

interface Subject {
    /**
     * Adiciona um observador
     * 
     * @param Observer $observer
     */
    public function attach(Observer $observer): void;
    
    /**
     * Remove um observador
     * 
     * @param Observer $observer
     */
    public function detach(Observer $observer): void;
    
    /**
     * Notifica todos os observadores
     * 
     * @param string $eventType Tipo do evento
     * @param array $data Dados do evento
     */
    public function notify(string $eventType, array $data): void;
}
?>