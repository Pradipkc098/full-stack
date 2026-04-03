<?php
require_once 'auth-check.php';
require_once 'database.php';
require_once 'vendor/autoload.php';

$total_query = "SELECT COUNT(*) as total FROM game_collection";
$total_result = $db_connection->query($total_query);
$total_games = 0;
if ($total_result) {
    $total_games = $total_result->fetch_assoc()['total'];
}

$columns_query = "SHOW COLUMNS FROM game_collection";
$columns_result = $db_connection->query($columns_query);
$existing_columns = [];
while ($column = $columns_result->fetch_assoc()) {
    $existing_columns[] = $column['Field'];
}

$date_column = null;
if (in_array('date_added', $existing_columns)) {
    $date_column = 'date_added';
} elseif (in_array('release_year', $existing_columns)) {
    $date_column = 'release_year';
} elseif (in_array('released_date', $existing_columns)) {
    $date_column = 'released_date';
}

$recent_games = 0;
if ($date_column) {
    if ($date_column === 'release_year') {
        $recent_query = "SELECT COUNT(*) as recent FROM game_collection WHERE $date_column = YEAR(CURDATE())";
    } elseif ($date_column === 'date_added') {
        $recent_query = "SELECT COUNT(*) as recent FROM game_collection WHERE YEAR($date_column) = YEAR(CURDATE())";
    } else {
        $recent_query = "SELECT COUNT(*) as recent FROM game_collection WHERE YEAR($date_column) = YEAR(CURDATE())";
    }
    $recent_result = $db_connection->query($recent_query);
    if ($recent_result) {
        $recent_games = $recent_result->fetch_assoc()['recent'];
    }
}

$rating_column = null;
if (in_array('score', $existing_columns)) {
    $rating_column = 'score';
} elseif (in_array('rating', $existing_columns)) {
    $rating_column = 'rating';
}

$avg_rating = 'N/A';
if ($rating_column) {
    $avg_query = "SELECT AVG($rating_column) as avg FROM game_collection";
    $avg_result = $db_connection->query($avg_query);
    if ($avg_result) {
        $avg_row = $avg_result->fetch_assoc();
        $avg_rating = $avg_row['avg'] !== null ? round($avg_row['avg'], 1) : 'N/A';
    }
}

$recent_list = [];
$title_column = in_array('game_title', $existing_columns) ? 'game_title' : 'game_name';

if ($date_column && $title_column) {
    $year_column = in_array('release_year', $existing_columns) ? 'release_year' : 
                  (in_array('released_date', $existing_columns) ? 'YEAR(released_date)' : 'NULL');
    
    $recent_list_query = "SELECT $title_column, $year_column as release_year";
    if ($date_column === 'date_added') {
        $recent_list_query .= ", $date_column";
    }
    $recent_list_query .= " FROM game_collection ORDER BY ";
    
    if ($date_column === 'release_year') {
        $recent_list_query .= "release_year DESC";
    } else {
        $recent_list_query .= "$date_column DESC";
    }
    $recent_list_query .= " LIMIT 5";
    
    $recent_list_result = $db_connection->query($recent_list_query);
    if ($recent_list_result) {
        $recent_list = $recent_list_result->fetch_all(MYSQLI_ASSOC);
    }
}

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('dashboard.html.twig', [
    'session' => $_SESSION,
    'total_games' => $total_games,
    'recent_games' => $recent_games,
    'avg_rating' => $avg_rating,
    'recent_list' => $recent_list
]);
?>