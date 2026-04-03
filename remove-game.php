<?php
require_once 'auth-check.php';
require_once 'database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view-games.php');
    exit();
}

$game_id = intval($_GET['id']);

$check_stmt = $db_connection->prepare("SELECT game_title FROM game_collection WHERE game_id = ?");
$check_stmt->bind_param("i", $game_id);
$check_stmt->execute();
$result = $check_stmt->get_result();
$game = $result->fetch_assoc();
$check_stmt->close();

if (!$game) {
    header('Location: view-games.php');
    exit();
}

$delete_stmt = $db_connection->prepare("DELETE FROM game_collection WHERE game_id = ?");
$delete_stmt->bind_param("i", $game_id);

if ($delete_stmt->execute()) {
    $_SESSION['success_message'] = "Game '" . htmlspecialchars($game['game_title']) . "' removed successfully";
} else {
    $_SESSION['error_message'] = "Failed to delete game";
}

$delete_stmt->close();
header('Location: view-games.php');
?>