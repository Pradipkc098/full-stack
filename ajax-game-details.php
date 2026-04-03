<?php
require_once 'auth-check.php';
require_once 'database.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid game ID']);
    exit();
}

$game_id = intval($_GET['id']);

$stmt = $db_connection->prepare("SELECT game_title, game_summary, release_year, score FROM game_collection WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $game = $result->fetch_assoc();
    echo json_encode([
        'game_title' => htmlspecialchars($game['game_title']),
        'game_summary' => htmlspecialchars($game['game_summary']),
        'release_year' => $game['release_year'],
        'score' => $game['score']
    ]);
} else {
    echo json_encode(['error' => 'Game not found']);
}
?>