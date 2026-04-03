<?php
require_once 'auth-check.php';
require_once 'database.php';
require_once 'vendor/autoload.php';

$title_keyword = trim($_GET['title_keyword'] ?? '');
$desc_keyword = trim($_GET['desc_keyword'] ?? '');
$min_rating = $_GET['min_rating'] ?? '';
$max_rating = $_GET['max_rating'] ?? '';
$year = $_GET['year'] ?? '';

$where = [];
$params = [];
$types = '';

if ($title_keyword) {
    $where[] = "game_title LIKE ?";
    $params[] = "%$title_keyword%";
    $types .= 's';
}

if ($desc_keyword) {
    $where[] = "game_summary LIKE ?";
    $params[] = "%$desc_keyword%";
    $types .= 's';
}

if (is_numeric($min_rating)) {
    $where[] = "score >= ?";
    $params[] = floatval($min_rating);
    $types .= 'd';
}

if (is_numeric($max_rating)) {
    $where[] = "score <= ?";
    $params[] = floatval($max_rating);
    $types .= 'd';
}

if (is_numeric($year)) {
    $where[] = "release_year = ?";
    $params[] = intval($year);
    $types .= 'i';
}

$sql = "SELECT game_id, game_title, release_year, score, game_summary FROM game_collection";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY release_year DESC";

$stmt = $db_connection->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('search-results.html.twig', [
    'session' => $_SESSION,
    'results' => $results,
    'criteria' => [
        'title_keyword' => $title_keyword,
        'desc_keyword' => $desc_keyword,
        'min_rating' => $min_rating,
        'max_rating' => $max_rating,
        'year' => $year
    ]
]);
?>