<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    echo "No user selected!";
    exit();
}

$user_id = $_GET['user_id'];

// Delete user
$sql_delete = "DELETE FROM users WHERE id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->execute([$user_id]);

echo "User deleted successfully!";
header("Location: manage_users.php"); // Redirect back to the user management page
?>
