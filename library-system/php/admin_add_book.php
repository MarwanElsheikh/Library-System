<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $copies_available = $_POST['copies_available'];
    $cover_image = null;

    // Handle file upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = uniqid() . '_' . basename($_FILES['cover_image']['name']);
        $upload_path = $upload_dir . $file_name;

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path)) {
            $cover_image = $upload_path;
        }
    }

    // Insert book into the database
    $sql = "INSERT INTO books (title, author, genre, published_year, copies_available, cover_image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $author, $genre, $published_year, $copies_available, $cover_image]);

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .note {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: -10px;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Book</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" required>
            </div>

            <div class="form-group">
                <label for="published_year">Published Year</label>
                <input type="number" id="published_year" name="published_year" required>
            </div>

            <div class="form-group">
                <label for="copies_available">Available Copies</label>
                <input type="number" id="copies_available" name="copies_available" required>
            </div>

            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <input type="file" id="cover_image" name="cover_image">
                <p class="note">Optional: Upload an image file for the book cover.</p>
            </div>

            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>
