<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch all available books (only books with available copies)
$sql = "SELECT * FROM books WHERE copies_available > 0";
$stmt = $conn->prepare($sql);
$stmt->execute();
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            width: 60px; /* Initial narrow width */
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            transition: width 0.3s ease-in-out; /* Smooth transition for width change */
            overflow: hidden;
        }

        .sidebar:hover {
            width: 250px; /* Expanded width on hover */
        }

        /* Sidebar links */
        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
            opacity: 0; /* Initially hidden */
        }

        .sidebar:hover a {
            opacity: 1; /* Links appear when the sidebar is expanded */
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .sidebar h2 {
            display: block;
            font-size: 18px;
            color: white;
            text-align: center;
            margin: 0;
            opacity: 0; /* Initially hidden */
        }

        .sidebar:hover h2 {
            opacity: 1; /* Show the name when the sidebar is expanded */
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

        /* Custom Header Style */
        .custom-header {
            background-color: #2ecc71;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.7), 0 0 30px rgba(255, 255, 255, 0.6);
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        /* Glowing animation */
        @keyframes glow {
            0% {
                text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.7), 0 0 30px rgba(255, 255, 255, 0.6);
            }
            50% {
                text-shadow: 0 0 20px rgba(255, 255, 255, 1), 0 0 40px rgba(255, 255, 255, 0.8), 0 0 60px rgba(255, 255, 255, 0.5);
            }
            100% {
                text-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.7), 0 0 30px rgba(255, 255, 255, 0.6);
            }
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
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
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

        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
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

        .video-container {
            margin-top: 20px;
            text-align: center;
        }

        video {
            width: 100%;
            height: auto;
            border-radius: 5px;
            max-width: 800px;
        }

        .controls {
            margin-top: 10px;
        }

        /* Footer styles */
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: right;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }

        .footer span {
            color: #ecf0f1;
        }
    </style>
</head>
<body>

    <!-- Custom Header -->
    <div class="custom-header">
        Have a happy reading
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="text-align: center; color: #ecf0f1;">
            Welcome, <?php echo htmlspecialchars($user['name']); ?>
        </h2>
        <a href="user_borrowed_books.php">My Borrowed Books</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Welcome To The Book Club</h1>
        </div>

        <!-- Books Section -->
        <h2>Browse Books</h2>
        <table>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Available Copies</th>
                <th>Action</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td>
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                    <td><?php echo $book['copies_available']; ?></td>
                    <td><a href="borrow_book.php?book_id=<?php echo $book['id']; ?>">Borrow</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Video Section -->
        <h2>View Our Shop</h2>
        <h2>We Are Waiting For You</h2>
        <div class="video-container">
            <video id="videoPlayer" controls>
                <source src="sample_video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>Contact us</span>: help@gmail.com +201111111111
    </div>

</body>
</html>
