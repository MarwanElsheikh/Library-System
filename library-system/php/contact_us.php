<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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

        .content {
            text-align: center;
            margin-top: 20px;
        }

        .content h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .content img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 10px;
            margin-bottom: 20px;
            cursor: pointer; /* Make it clear the image is clickable */
        }

        .contact-info {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-top: 20px;
        }

        .contact-info span {
            display: inline-block;
            margin: 0 10px;
            font-weight: bold;
            color: #3498db;
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

        /* Modal for enlarged image */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            max-width: 90%;
            max-height: 80%;
        }

        .modal img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            background-color: transparent;
            border: none;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Contact Us</h1>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Our Location</h2>
        <!-- Clickable image -->
        <img src="location.png" alt="Library Location" id="image" onclick="openModal();">
        <div class="contact-info">
            <span>Email:</span> help@gmail.com 
            <span>|</span> 
            <span>Phone:</span> +201111111111
        </div>
    </div>

    <!-- Modal for enlarged image -->
    <div id="myModal" class="modal" onclick="closeModal();">
        <button class="modal-close" onclick="closeModal();">&times;</button>
        <div class="modal-content">
            <img src="location.png" alt="Enlarged Library Location">
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>&copy; 2024 Library Management System</span>
    </div>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById('myModal').style.display = "flex";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('myModal').style.display = "none";
        }
    </script>

</body>
</html>
