<?php
session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['pt', 'en', 'es', 'fr'])) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_GET['lang'], time() + (365 * 24 * 60 * 60), '/');
}

$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $redirect");
exit;
?>