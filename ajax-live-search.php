<?php
require_once 'auth-check.php';
require_once 'database.php';

header('Content-Type: application/json');

$search_term = isset($_GET['term']) ? trim($_GET['term']) : '';

if (strlen($search_term) < 2) {
    echo json_encode([]);
    exit();
}

$stmt = $db_connection->prepare("SELECT game_id, game_title, release_year FROM game_collection WHERE game_title LIKE ? ORDER BY game_title LIMIT 10");
$like_term = "%" . $search_term . "%";
$stmt->bind_param("s", $like_term);
$stmt->execute();
$result = $stmt->get_result();

$games = [];
while ($row = $result->fetch_assoc()) {
    $games[] = [
        'game_id' => $row['game_id'],
        'game_title' => htmlspecialchars($row['game_title']),
        'release_year' => $row['release_year']
    ];
}

echo json_encode($games);
?>