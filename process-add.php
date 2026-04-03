<?php
require_once 'auth-check.php';
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-game.php');
    exit();
}

$game_title = trim($_POST['game_title'] ?? '');  
$game_summary = trim($_POST['game_description'] ?? '');  
$release_year = $_POST['release_year'] ?? '';  
$score = $_POST['game_rating'] ?? '';  

$errors = [];

if (empty($game_title) || strlen($game_title) > 200) {
    $errors[] = "Game title must be between 1-200 characters";
}

if (empty($game_summary)) {
    $errors[] = "Game description is required";
}

if (empty($release_year)) {
    $errors[] = "Release year is required";
} elseif (!is_numeric($release_year) || $release_year < 1970 || $release_year > 2025) {
    $errors[] = "Release year must be between 1970-2025";
}

if (!is_numeric($score) || $score < 1 || $score > 5) {
    $errors[] = "Rating must be between 1-5";
}

if (empty($errors)) {
    $stmt = $db_connection->prepare("INSERT INTO game_collection (game_title, game_summary, release_year, score) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssid", $game_title, $game_summary, $release_year, $score);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Game added successfully!";
        header('Location: view-games.php');
    } else {
        $_SESSION['error_message'] = "Database error: " . $stmt->error;
        header('Location: add-game.php');
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = implode("<br>", $errors);
    header('Location: add-game.php');
}
?>