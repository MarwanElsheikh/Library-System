<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get book ID from the URL
if (!isset($_GET['book_id'])) {
    echo "Book not found!";
    exit();
}

$book_id = $_GET['book_id'];

// Step 1: Delete all borrow records for this book
$sql_delete_borrow = "DELETE FROM borrow WHERE book_id = ?";
$stmt_delete_borrow = $conn->prepare($sql_delete_borrow);
$stmt_delete_borrow->execute([$book_id]);

// Step 2: Now delete the book from the books table
$sql_delete_book = "DELETE FROM books WHERE id = ?";
$stmt_delete_book = $conn->prepare($sql_delete_book);
$stmt_delete_book->execute([$book_id]);

echo "Book and associated borrow records deleted successfully!";
header("Location: admin_dashboard.php"); // Redirect back to the dashboard after deletion
exit();
?>
