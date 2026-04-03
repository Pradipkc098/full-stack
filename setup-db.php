<?php
require_once 'database.php';

echo "<h2>Game Collection Database Setup</h2>";
echo "<p style='color: red; font-weight: bold;'>This will reset your database with the correct structure!</p>";

$db_connection->query("DROP TABLE IF EXISTS game_collection");

$create_table = "CREATE TABLE game_collection (
    game_id INT AUTO_INCREMENT PRIMARY KEY,
    game_title VARCHAR(200) NOT NULL,
    game_summary TEXT,
    release_year YEAR,
    score DECIMAL(2,1),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($db_connection->query($create_table)) {
    echo "✅ Table created with correct structure<br>";
    
    $samples = [
        ["The Legend of Zelda: Breath of the Wild", "Open-world adventure", 2017, 4.8],
        ["Elden Ring", "Action RPG", 2022, 4.9],
        ["Minecraft", "Sandbox building", 2011, 4.7],
        ["Portal 2", "Puzzle game", 2011, 4.8],
        ["The Witcher 3", "Action RPG", 2015, 4.9]
    ];
    
    $stmt = $db_connection->prepare("INSERT INTO game_collection (game_title, game_summary, release_year, score) VALUES (?, ?, ?, ?)");
    $count = 0;
    foreach ($samples as $game) {
        $stmt->bind_param("ssid", $game[0], $game[1], $game[2], $game[3]);
        if ($stmt->execute()) $count++;
    }
    echo "✅ Added $count sample games<br>";
} else {
    echo "❌ Error: " . $db_connection->error . "<br>";
}

echo "<hr>";
echo "<h3>Setup Complete!</h3>";
echo "<p><a href='view-games.php'>View Games</a> | <a href='index.html'>Home Page</a></p>";
?>