<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login-page.php?session_expired=1');
    exit();
}

$session_timeout = 1800;
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $session_timeout)) {
    session_unset();
    session_destroy();
    header('Location: login-page.php?session_expired=1');
    exit();
}

$_SESSION['login_time'] = time();
?>