<?php

require_once __DIR__ . '/../config.php';

session_start();
// Authorising admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'moderator') {
    header("Location: ../login.php");
    exit;
}
?>
