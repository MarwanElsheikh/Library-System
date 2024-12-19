<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a book ID is provided in the URL
if (!isset($_GET['book_id'])) {
    echo "No book selected!";
    exit();
}

$book_id = $_GET['book_id'];

// Fetch the book details
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found!";
    exit();
}

// Check if there are available copies to borrow
if ($book['copies_available'] > 0) {
    // Insert a borrow record into the borrow table
    $sql_borrow = "INSERT INTO borrow (user_id, book_id, borrow_date) VALUES (?, ?, NOW())";
    $stmt_borrow = $conn->prepare($sql_borrow);
    $stmt_borrow->execute([$user_id, $book_id]);

    // Decrease the number of available copies
    $sql_update = "UPDATE books SET copies_available = copies_available - 1 WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$book_id]);

    echo "You have successfully borrowed the book: " . $book['title'];
} else {
    echo "Sorry, no copies are available for this book.";
}
?>
