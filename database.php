<?php

$host = "localhost";
$user = "2449500";
$password = "Pradip098";
$database = "db2449500";

$db_connection = new mysqli("localhost", "2449500", "Pradip098", "db2449500");

if ($db_connection->connect_errno) {
    error_log("Database connection failed for Pradip KC (2449500)");
    die("System maintenance in progress. Please check back soon.");
}

$db_connection->set_charset("utf8mb4");
?>