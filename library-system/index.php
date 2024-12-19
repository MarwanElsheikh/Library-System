<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .header {
            text-align: center;
            margin-top: -80px; /* To push the text closer to the top */
        }

        .header h1 {
            font-size: 3rem;
            color: #34495e;
            margin: 0;
        }

        .button-container {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 30px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 20px 50px;
            cursor: pointer;
            font-size: 1.5rem;
            border-radius: 10px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .sub-text {
            margin-top: 20px;
            text-align: center;
            font-size: 1.2rem;
            color: #2c3e50;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
            position: absolute;
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

    <!-- Header -->
    <div class="header">
        <h1>Welcome to the Library!</h1>
    </div>

    <!-- Buttons -->
    <div class="button-container">
        <button onclick="window.location.href='php/login.php'">Login</button>
        <button onclick="window.location.href='php/register.php'">Sign Up</button>
        <button onclick="window.location.href='php/contact_us.php'">Contact Us</button> <!-- This button goes to the Contact Us page -->
    </div>

    <!-- Subtext -->
    <div class="sub-text">
        You're One Click Away From Your Dream Book !
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>&copy; 2024 Library Management System</span>
    </div>

</body>
</html>
