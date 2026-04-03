<?php
require_once 'auth-check.php';
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-games.php');
    exit();
}

if (!isset($_POST['game_id']) || !is_numeric($_POST['game_id'])) {
    header('Location: view-games.php');
    exit();
}

$game_id = intval($_POST['game_id']);
$title = trim($_POST['game_title'] ?? '');
$description = trim($_POST['game_description'] ?? '');
$year = $_POST['release_year'] ?? '';
$rating = $_POST['game_rating'] ?? '';

$validation_errors = [];

if (empty($title) || strlen($title) > 200) {
    $validation_errors[] = "Invalid title length";
}

if (empty($description)) {
    $validation_errors[] = "Description required";
}

if (!is_numeric($year) || $year < 1970 || $year > 2025) {
    $validation_errors[] = "Invalid year";
}

if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    $validation_errors[] = "Invalid rating";
}

if (empty($validation_errors)) {
    $stmt = $db_connection->prepare("UPDATE game_collection SET game_title = ?, game_summary = ?, release_year = ?, score = ? WHERE game_id = ?");
    $stmt->bind_param("ssidi", $title, $description, $year, $rating, $game_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Game updated successfully";
        header('Location: game-info.php?id=' . $game_id);
    } else {
        $_SESSION['edit_error'] = "Update failed: " . $stmt->error;
        header('Location: modify-game.php?id=' . $game_id);
    }
    $stmt->close();
} else {
    $_SESSION['edit_error'] = "Please correct: " . implode(", ", $validation_errors);
    header('Location: modify-game.php?id=' . $game_id);
}
?>