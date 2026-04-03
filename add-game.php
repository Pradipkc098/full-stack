<?php
require_once 'auth-check.php';
require_once 'vendor/autoload.php';

$error_message = $_SESSION['error_message'] ?? '';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('add-game.html.twig', [
    'session' => $_SESSION,
    'error_message' => $error_message
]);

unset($_SESSION['error_message']);
?>