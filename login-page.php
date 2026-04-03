<?php
session_start();
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: dashboard.php');
    exit();
}

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$error_code = isset($_GET['auth_error']) ? 1 : 0;

echo $twig->render('login.html.twig', [
    'session' => $_SESSION,
    'error_code' => $error_code
]);
?>