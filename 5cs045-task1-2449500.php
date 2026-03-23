<?php
// Database credentials - update these with your actual details
$host     = "localhost";
$dbname   = "2449500";   // your database name
$username = "2449500";   // usually same as dbname on mi-linux
$password = "Pradip098"; // your actual DB password

// Connect
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query
$result = $conn->query("SELECT Book_name, Genre, Price, Date_of_release FROM books");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background-color: #4a90d9; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Books</h2>
    <table>
        <tr>
            <th>Book name</th>
            <th>Genre</th>
            <th>Price</th>
            <th>Date of release</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Book_name']) ?></td>
            <td><?= htmlspecialchars($row['Genre']) ?></td>
            <td>£<?= number_format($row['Price'], 2) ?></td>
            <td><?= date('d/m/Y', strtotime($row['Date_of_release'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>