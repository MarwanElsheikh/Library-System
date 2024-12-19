<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users from the database
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* Reuse the same CSS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        header h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        nav {
            text-align: center;
            margin-bottom: 20px;
        }
        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #007BFF;
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Users</h1>
        <nav>
            <a href="admin_dashboard.php">Back to Manage Books</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <h2>Users List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="admin_edit_user.php?user_id=<?php echo $user['id']; ?>">Edit</a>
                    <a href="admin_delete_user.php?user_id=<?php echo $user['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
