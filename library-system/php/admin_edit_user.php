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

// Fetch user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found!";
    exit();
}

// Handle form submission for editing user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user details
    $sql_update = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$name, $email, $role, $user_id]);

    $success_message = "User updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        header h1 {
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
        form {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            font-size: 16px;
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit User</h1>
        <nav>
            <a href="manage_users.php">Back to Manage Users</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <?php if (isset($success_message)): ?>
        <div class="message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form action="admin_edit_user.php?user_id=<?php echo $user['id']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
        </select>

        <button type="submit">Update User</button>
    </form>
</body>
</html>
