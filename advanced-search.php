<?php
require_once 'auth-check.php';
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('search.html.twig', [
    'session' => $_SESSION
]);
?>