<?php
session_start(); // Start a session to track the user's login status

// Database connection
$db = new mysqli("localhost", "root", "", "nyxify-registration");

if ($db->connect_error) {
    die("Connection Failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $passwords = $_POST['passwords'];
    $captcha = $_POST['captcha'];

    // Validate CAPTCHA
    if ($captcha != $_SESSION['captcha_code']) {
        $_SESSION['error'] = 'Incorrect CAPTCHA!';
        header("Location: ../../index.php?page=login&error=captcha");
        exit();
    }

    // Query to check if the user exists with the provided username and password
    $query = "SELECT * FROM persons WHERE username = ? AND passwords = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $username, $passwords);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, log them in
        $user = $result->fetch_assoc();
        $_SESSION['logged_in'] = 'true';
        $_SESSION['username'] = $username; // Store username in session
        
        // Check if the user is an admin (check `is_admin` field)
        if ($user['is_admin'] == 1) {
            $_SESSION['role'] = 'admin'; // Set role to admin
        } else {
            $_SESSION['role'] = 'user'; // Set role to user
        }

        // Redirect to home page after successful login
        header("Location: ../../index.php?page=home");
        exit();
    } else {
        // Incorrect credentials, redirect to login page with error message
        $_SESSION['error'] = 'Invalid username or password!';
        header("Location: ../../index.php?page=login&error=invalid_credentials");
        exit();
    }
}

if (isset($_GET['logout'])) {
    // Log out the user and destroy the session
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect to the home page after logout
    exit();
}
?>
