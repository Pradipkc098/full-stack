<?php
require_once 'auth-check.php';
require_once 'database.php';
require_once 'vendor/autoload.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view-games.php');
    exit();
}

$game_id = intval($_GET['id']);

$stmt = $db_connection->prepare("SELECT * FROM game_collection WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();
$stmt->close();

if (!$game) {
    header('Location: view-games.php');
    exit();
}

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('game-details.html.twig', [
    'session' => $_SESSION,
    'game' => $game
]);
?>