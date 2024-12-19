<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all books from the database
$sql = "SELECT * FROM books";
$stmt = $conn->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
            text-align: left;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        a:hover {
            color: #2980b9;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
        }

        .no-cover {
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="text-align: center; color: #ecf0f1;">Admin Dashboard</h2>
        <a href="admin_add_book.php">Add New Book</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Welcome To The Admin Page</h1>
        </div>

        <h2>Manage Books</h2>
        <table>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Year</th>
                <th>Available Copies</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($books as $book): ?>
                <tr>
                    <td>
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover" class="book-cover">
                        <?php else: ?>
                            <span class="no-cover">No Cover</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                    <td><?php echo htmlspecialchars($book['published_year']); ?></td>
                    <td><?php echo $book['copies_available']; ?></td>
                    <td>
                        <a href="admin_edit_book.php?book_id=<?php echo $book['id']; ?>">Edit</a>
                        <a href="admin_delete_book.php?book_id=<?php echo $book['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>