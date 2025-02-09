<?php
// Database connection
require "../file/database.php";
$db = new Database();

// Check if the user ID is passed in the request
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Prepare the SQL query to delete the user
    $sql = "DELETE FROM persons WHERE p_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Execute the query and check if the deletion was successful
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $db->close();
} else {
    echo "No user ID provided.";
}
?>
