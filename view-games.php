<?php
require_once 'auth-check.php';
require_once 'database.php';
require_once 'vendor/autoload.php';

$columns_query = "SHOW COLUMNS FROM game_collection";
$columns_result = $db_connection->query($columns_query);
$existing_columns = [];
while ($column = $columns_result->fetch_assoc()) {
    $existing_columns[] = $column['Field'];
}

$id_column = in_array('game_id', $existing_columns) ? 'game_id' : 'id';
$title_column = in_array('game_title', $existing_columns) ? 'game_title' : 
                (in_array('game_name', $existing_columns) ? 'game_name' : 'title');
$year_column = in_array('release_year', $existing_columns) ? 'release_year' : 
               (in_array('released_date', $existing_columns) ? 'YEAR(released_date)' : 'NULL');
$rating_column = in_array('score', $existing_columns) ? 'score' : 
                 (in_array('rating', $existing_columns) ? 'rating' : 'NULL');
$desc_column = in_array('game_summary', $existing_columns) ? 'game_summary' : 
               (in_array('game_description', $existing_columns) ? 'game_description' : 'description');

if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $db_connection->prepare("DELETE FROM game_collection WHERE $id_column = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Game deleted successfully";
    } else {
        $_SESSION['error_message'] = "Error deleting game";
    }
    header('Location: view-games.php');
    exit();
}

$sort = $_GET['sort'] ?? 'id';
$order_by = match($sort) {
    'name' => "$title_column ASC",
    'year' => "$year_column DESC",
    'rating' => "$rating_column DESC",
    default => "$id_column DESC"
};

$select_columns = [$id_column];
if (in_array($title_column, $existing_columns)) $select_columns[] = $title_column;
if ($year_column !== 'NULL' && in_array(str_replace('YEAR(', '', str_replace(')', '', $year_column)), $existing_columns)) {
    $select_columns[] = $year_column . ($year_column === 'release_year' ? '' : '') . ' as release_year';
} else {
    $select_columns[] = 'NULL as release_year';
}
if (in_array(str_replace(' as score', '', $rating_column), $existing_columns)) {
    $select_columns[] = $rating_column . ($rating_column === 'score' ? '' : ' as score');
} else {
    $select_columns[] = 'NULL as score';
}
if (in_array($desc_column, $existing_columns)) $select_columns[] = $desc_column;

$query = "SELECT " . implode(', ', $select_columns) . " FROM game_collection ORDER BY $order_by";
$result = $db_connection->query($query);
$games = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$count_query = "SELECT COUNT(*) as total FROM game_collection";
$count_result = $db_connection->query($count_query);
$total_count = $count_result ? $count_result->fetch_assoc()['total'] : 0;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('view-games.html.twig', [
    'session' => $_SESSION,
    'games' => $games,
    'total_count' => $total_count,
    'success_message' => $_SESSION['success_message'] ?? '',
    'error_message' => $_SESSION['error_message'] ?? '',
    'title_column' => $title_column
]);

unset($_SESSION['success_message'], $_SESSION['error_message']);
?>