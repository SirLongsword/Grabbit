<?php
session_start();
require_once '../config.php';

// Only allow admin role to delete users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

// Delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = (int) $_POST['id'];

    // Delete related data
    $pdo->prepare("DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?")->execute([$userId, $userId]);
    $pdo->prepare("DELETE FROM listings WHERE user_id = ?")->execute([$userId]);
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
}

// Redirect
header("Location: " . BASE_URL . "/admin/manage_users.php");
exit();
