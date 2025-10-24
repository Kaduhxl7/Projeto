<?php

class Language {
    private static $instance = null;
    private $currentLanguage = 'pt';
    private $translations = [];
    private $availableLanguages = [
        'pt' => ['name' => 'Português', 'flag' => '🇧🇷'],
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
        'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷']
    ];

    private function __construct() {
        $this->initializeLanguage();
        $this->loadTranslations();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeLanguage() {
        // Prioridade: URL > Session > Cookie > Browser > Default
        if (isset($_GET['lang']) && $this->isValidLanguage($_GET['lang'])) {
            $this->currentLanguage = $_GET['lang'];
            $_SESSION['language'] = $this->currentLanguage;
            setcookie('language', $this->currentLanguage, time() + (365 * 24 * 60 * 60), '/');
        } elseif (isset($_SESSION['language']) && $this->isValidLanguage($_SESSION['language'])) {
            $this->currentLanguage = $_SESSION['language'];
        } elseif (isset($_COOKIE['language']) && $this->isValidLanguage($_COOKIE['language'])) {
            $this->currentLanguage = $_COOKIE['language'];
            $_SESSION['language'] = $this->currentLanguage;
        } else {
            // Detectar idioma do browser
            $browserLang = $this->detectBrowserLanguage();
            if ($browserLang) {
                $this->currentLanguage = $browserLang;
                $_SESSION['language'] = $this->currentLanguage;
                setcookie('language', $this->currentLanguage, time() + (365 * 24 * 60 * 60), '/');
            }
        }
    }

    private function detectBrowserLanguage() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }

        $acceptedLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($acceptedLanguages as $lang) {
            $lang = trim(strtolower(substr($lang, 0, 2)));
            if ($this->isValidLanguage($lang)) {
                return $lang;
            }
        }
        return null;
    }

    private function isValidLanguage($lang) {
        return array_key_exists($lang, $this->availableLanguages);
    }

    public function loadTranslations() {
        $langFile = __DIR__ . "/../languages/{$this->currentLanguage}.php";
        if (file_exists($langFile)) {
            $this->translations = include $langFile;
        } else {
            // Fallback para português
            $fallbackFile = __DIR__ . "/../languages/pt.php";
            if (file_exists($fallbackFile)) {
                $this->translations = include $fallbackFile;
            }
        }
    }

    public function get($key, $params = []) {
        $keys = explode('.', $key);
        $value = $this->translations;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                // Fallback: tentar carregar do idioma padrão (português)
                if ($this->currentLanguage !== 'pt') {
                    $fallbackValue = $this->getFallbackTranslation($key);
                    if ($fallbackValue !== $key) {
                        return $fallbackValue;
                    }
                }
                return $key; // Retorna a chave se não encontrar tradução
            }
        }

        // Substituir parâmetros
        if (!empty($params) && is_string($value)) {
            foreach ($params as $param => $replacement) {
                $value = str_replace("{{$param}}", $replacement, $value);
            }
        }

        return $value;
    }

    private function getFallbackTranslation($key) {
        $fallbackFile = __DIR__ . "/../languages/pt.php";
        if (file_exists($fallbackFile)) {
            $fallbackTranslations = include $fallbackFile;
            $keys = explode('.', $key);
            $value = $fallbackTranslations;
            
            foreach ($keys as $k) {
                if (isset($value[$k])) {
                    $value = $value[$k];
                } else {
                    return $key;
                }
            }
            
            return $value;
        }
        return $key;
    }

    public function getCurrentLanguage() {
        return $this->currentLanguage;
    }

    public function getAvailableLanguages() {
        return $this->availableLanguages;
    }

    public function getCurrentLanguageName() {
        return $this->availableLanguages[$this->currentLanguage]['name'];
    }

    public function getCurrentLanguageFlag() {
        return $this->availableLanguages[$this->currentLanguage]['flag'];
    }

    public function setLanguage($lang) {
        if ($this->isValidLanguage($lang)) {
            $this->currentLanguage = $lang;
            $_SESSION['language'] = $lang;
            setcookie('language', $lang, time() + (365 * 24 * 60 * 60), '/');
            $this->loadTranslations();
            return true;
        }
        return false;
    }
}

// Função global para facilitar o uso
function __($key, $params = []) {
    return Language::getInstance()->get($key, $params);
}

// Função para obter idioma atual
function getCurrentLang() {
    return Language::getInstance()->getCurrentLanguage();
}