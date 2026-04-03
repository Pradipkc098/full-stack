<?php
require_once 'database.php';

echo "<h2>Database Diagnostic</h2>";

echo "<h3>Tables in database:</h3>";
$tables = $db_connection->query("SHOW TABLES");
while ($table = $tables->fetch_array()) {
    echo $table[0] . "<br>";
}

echo "<h3>game_collection structure:</h3>";
$columns = $db_connection->query("SHOW COLUMNS FROM game_collection");
if ($columns) {
    echo "<table border='1'>";
    echo "<tr><th>Column</th><th>Type</th></tr>";
    while ($col = $columns->fetch_assoc()) {
        echo "<tr><td>" . $col['Field'] . "</td><td>" . $col['Type'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $db_connection->error;
}

echo "<h3>Recommendation:</h3>";
echo "<p><strong>Run setup-db.php to create the correct table structure:</strong></p>";
echo "<p><a href='setup-db.php' style='color: red; font-weight: bold;'>Click here to run setup-db.php</a></p>";
echo "<p style='color: orange;'>Warning: This will delete any existing game data!</p>";
?>