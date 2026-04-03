<?php
session_start();
require_once 'database.php';

if (!isset($_POST['security_answer']) || intval($_POST['security_answer']) !== 7) {
    header('Location: login-page.php?auth_error=1');
    exit();
}

$username = trim($_POST['user_identifier'] ?? '');
$password = $_POST['user_password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: login-page.php?auth_error=1');
    exit();
}

$query = "SELECT user_id, login_name, pass_hash FROM system_users WHERE login_name = ?";
$stmt = $db_connection->prepare($query);

if (!$stmt) {
    error_log("Login preparation failed");
    header('Location: login-page.php?auth_error=1');
    exit();
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
    
    if (password_verify($password, $user_data['pass_hash'])) {
        $_SESSION['authenticated'] = true;
        $_SESSION['user_id'] = $user_data['user_id'];
        $_SESSION['user_name'] = $user_data['login_name'];
        $_SESSION['login_time'] = time();
        
        session_regenerate_id(true);
        
        header('Location: dashboard.php');
        exit();
    }
}

header('Location: login-page.php?auth_error=1');
exit();
?>