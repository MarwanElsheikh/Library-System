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

// Fetch the book details from the database
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "Book not found!";
    exit();
}

// Handle the form submission to update the book details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];
    $copies_available = $_POST['copies_available'];

    // Handle image upload
    $cover_image = $book['cover_image']; // Default to current image

    if (!empty($_FILES['cover_image']['name'])) {
        // Validate image file
        $target_dir = "uploads/"; // Directory to store uploaded images
        $target_file = $target_dir . basename($_FILES['cover_image']['name']);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image
        $check = getimagesize($_FILES['cover_image']['tmp_name']);
        if ($check !== false) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                // Update cover image in the database
                $cover_image = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }
        } else {
            echo "File is not an image.";
            exit();
        }
    }

    // Update book details in the database
    $sql_update = "UPDATE books SET title = ?, author = ?, genre = ?, published_year = ?, copies_available = ?, cover_image = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$title, $author, $genre, $published_year, $copies_available, $cover_image, $book_id]);

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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

        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            color: #34495e;
        }

        input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 16px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Book</h1>
        <form action="admin_edit_book.php?book_id=<?php echo $book['id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo $book['genre']; ?>" required>

            <label for="published_year">Published Year:</label>
            <input type="number" id="published_year" name="published_year" value="<?php echo $book['published_year']; ?>" required>

            <label for="copies_available">Copies Available:</label>
            <input type="number" id="copies_available" name="copies_available" value="<?php echo $book['copies_available']; ?>" required>

            <label for="cover_image">Cover Image:</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*">

            <?php if (!empty($book['cover_image'])): ?>
                <p>Current Cover Image:</p>
                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Current Book Cover" width="100">
            <?php endif; ?>

            <button type="submit">Update Book</button>
        </form>
        <a class="back-link" href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
