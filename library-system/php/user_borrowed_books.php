<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch borrowed books by the logged-in user
$sql = "SELECT br.id AS borrow_id, b.id AS book_id, b.title, b.author, br.borrow_date, br.return_date FROM borrow br 
        JOIN books b ON br.book_id = b.id 
        WHERE br.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$borrowed_books = $stmt->fetchAll();

// Handle return book request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'])) {
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id']; // Get the book ID from the form
    $return_date = date('Y-m-d'); // Get the current date (this will now be when the button is clicked)

    // Update the return date for the borrowed book
    $update_sql = "UPDATE borrow SET return_date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$return_date, $borrow_id]);

    // Update the number of available copies in the books table
    $update_copies_sql = "UPDATE books SET copies_available = copies_available + 1 WHERE id = ?";
    $update_copies_stmt = $conn->prepare($update_copies_sql);
    $update_copies_stmt->execute([$book_id]);

    // Refresh the page to show updated data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Custom Header */
        .custom-header {
            background-color: #2ecc71;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Footer styles */
        .footer {
            background-color: #4CAF50;
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

    <header>
        <h1>Your Borrowed Books</h1>
        <nav>
            <a href="user_dashboard.php">Back to Dashboard</a>
        </nav>
    </header>

    <div class="container">
        <?php if ($borrowed_books): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Borrowed Date</th>
                        <th>Return Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowed_books as $borrowed_book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($borrowed_book['title']); ?></td>
                            <td><?php echo htmlspecialchars($borrowed_book['author']); ?></td>
                            <td><?php echo htmlspecialchars($borrowed_book['borrow_date']); ?></td>
                            <td><?php echo $borrowed_book['return_date'] ? htmlspecialchars($borrowed_book['return_date']) : 'Not returned yet'; ?></td>
                            <td>
                                <?php if (!$borrowed_book['return_date']): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="borrow_id" value="<?php echo $borrowed_book['borrow_id']; ?>">
                                        <input type="hidden" name="book_id" value="<?php echo $borrowed_book['book_id']; ?>"> <!-- Pass the book ID -->
                                        <button type="submit">Return</button>
                                    </form>
                                <?php else: ?>
                                    Returned
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not borrowed any books yet.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>Contact us</span>: help@gmail.com +201111111111
    </div>
</body>
</html>
