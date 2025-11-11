<?php
/**
 * Model TermoAceite
 * Gerencia os dados de aceite de termos de uso
 */
class TermoAceite {
    private $id;
    private $id_usuario;
    private $termos_aceitos;
    private $data_aceite;
    private $assinatura;
    private $ip_address;
    private $user_agent;

    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->id_usuario = $data['id_usuario'] ?? null;
        $this->termos_aceitos = $data['termos_aceitos'] ?? false;
        $this->data_aceite = $data['data_aceite'] ?? null;
        $this->assinatura = $data['assinatura'] ?? null;
        $this->ip_address = $data['ip_address'] ?? null;
        $this->user_agent = $data['user_agent'] ?? null;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getTermosAceitos() { return $this->termos_aceitos; }
    public function getDataAceite() { return $this->data_aceite; }
    public function getAssinatura() { return $this->assinatura; }
    public function getIpAddress() { return $this->ip_address; }
    public function getUserAgent() { return $this->user_agent; }

    // Setters
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setTermosAceitos($termos_aceitos) { $this->termos_aceitos = $termos_aceitos; }
    public function setAssinatura($assinatura) { $this->assinatura = $assinatura; }
    public function setIpAddress($ip_address) { $this->ip_address = $ip_address; }
    public function setUserAgent($user_agent) { $this->user_agent = $user_agent; }
}
