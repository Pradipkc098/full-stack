<?php
require_once 'auth-check.php';
require_once 'database.php';

header('Content-Type: application/json');

$query = "SELECT 
            CASE 
                WHEN score >= 4.5 THEN '4.5-5.0'
                WHEN score >= 4.0 THEN '4.0-4.4'
                WHEN score >= 3.5 THEN '3.5-3.9'
                WHEN score >= 3.0 THEN '3.0-3.4'
                WHEN score >= 2.0 THEN '2.0-2.9'
                ELSE '1.0-1.9'
            END as rating_range,
            COUNT(*) as game_count
          FROM game_collection 
          GROUP BY rating_range 
          ORDER BY rating_range DESC";

$result = $db_connection->query($query);
$stats = [];

while ($row = $result->fetch_assoc()) {
    $stats[] = [
        'rating' => $row['rating_range'],
        'count' => $row['game_count']
    ];
}

echo json_encode($stats);
?>