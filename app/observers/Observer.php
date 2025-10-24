<?php
/**
 * Padrão Observer - Interface Observer
 * 
 * Define o contrato que todos os observadores devem implementar.
 * Permite que objetos sejam notificados automaticamente sobre mudanças
 * sem criar dependências diretas entre eles.
 */

interface Observer {
    /**
     * Método chamado quando o subject notifica os observadores
     * 
     * @param string $eventType Tipo do evento
     * @param array $data Dados do evento
     */
    public function update(string $eventType, array $data): void;
}
?>