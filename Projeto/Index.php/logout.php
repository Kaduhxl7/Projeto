<?php
// Para sair da conta
session_start();
session_destroy();
header('Location: Tela_Login.php');
exit();
?>
