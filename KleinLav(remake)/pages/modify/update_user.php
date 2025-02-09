<?php
// Include the database connection
// Include the database connection
require_once '../file/database.php';
$db = new Database();

// Check if the connection is successful
if ($db->connection->connect_error) {
    die("Connection failed: " . $db->connection->connect_error);
}

// Check if the POST request contains all required data
if (isset($_POST['user_id'], $_POST['fname'], $_POST['lname'], $_POST['course'], $_POST['username'], $_POST['password'])) {
    // Get the values from the POST data
    $user_id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $course = $_POST['course'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Make sure to hash the password if needed

    // Prepare the SQL query to update the user
    $update_sql = "UPDATE persons SET p_fname = ?, p_lname = ?, course = ?, username = ?, passwords = ? WHERE p_id = ?";
    $stmt = $db->prepare($update_sql);

    // Hash password before saving (if necessary)
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Bind the parameters
    $stmt->bind_param("sssssi", $fname, $lname, $course, $username, $password, $user_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "User updated successfully!";
    } else {
        echo "Error updating user: " . $stmt->error; // Provide the actual error message
    }
    
    // Close the statement
    $stmt->close();
} else {
    echo "Error: Missing data!";
}

// Close the database connection
$db->close();
?>
