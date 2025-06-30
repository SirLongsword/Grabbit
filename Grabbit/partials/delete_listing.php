<?php
require_once '../config.php';
session_start();

// Only allowing POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// User logged in?
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

// Validate listing ID
$listingId = isset($_POST['listing_id']) ? (int) $_POST['listing_id'] : 0;
if ($listingId <= 0) {
    exit('Invalid listing ID.');
}

// Fetch owner
$stmt = $pdo->prepare("SELECT user_id FROM listings WHERE id = ?");
$stmt->execute([$listingId]);
$listing = $stmt->fetch();

if ($listing) {
    $userId = (int) $_SESSION['user_id'];
    $userRole = $_SESSION['role'] ?? '';

    $isOwner = $userId === (int) $listing['user_id'];
    $isAdmin = $userRole === 'admin';
    $isMod   = $userRole === 'moderator';

    if ($isOwner || $isAdmin || $isMod) {
        $pdo->prepare("DELETE FROM listings WHERE id = ?")->execute([$listingId]);
    }
}

// Redirect
header("Location: " . BASE_URL . "/homepage.php");
exit();
