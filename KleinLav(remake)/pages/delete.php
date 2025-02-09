<?php

// Database connection
require "file/database.php";
$db = new Database();

// Initialize search variables
$search_query = "";
$search_term = "";

// Check if form is submitted via POST for search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    $search_term = $_POST['search_term']; // Get the search term from the form
    $search_query = " WHERE username LIKE ? OR p_fname LIKE ? OR p_lname LIKE ? OR p_id LIKE ?";
}

// Prepare the SQL query based on search term
$sql = "SELECT * FROM persons" . $search_query;
$stmt = $db->prepare($sql);

if (!empty($search_query)) {
    $search_term_like = "%" . $search_term . "%";
    $stmt->bind_param("ssss", $search_term_like, $search_term_like, $search_term_like, $search_term_like);
}

$stmt->execute();
$result = $stmt->get_result();

// Display the search form (accessible to all users)
echo "
<div style='display: flex; justify-content: center; margin: 10px 0 10px 0;  padding: 20px; border-radius: 8px;'>
    <form method='POST' action='' style='background-color: black; width: 400px; padding: 20px; border-radius: 8px; display: flex; justify-content: center;align-items: center;'>
        <label for='search_term' style='font-weight: bold; color: #fff; margin: 10px 10px 0 0;'>Search Users:</label>
        <input type='text' name='search_term' id='search_term' value='" . htmlspecialchars($search_term) . "' placeholder='Enter user ID, first name, last name, or username' style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #007BFF; background-color:rgb(2, 72, 54); color: white;' required>
        <input type='submit' value='Search' style='padding: 10px; margin-top: 10px; border-radius: 4px; border: 1px solid #007BFF; background-color:rgb(1, 163, 66); color: white; cursor: pointer; margin-left: 10px;'>
    </form>
</div>

";

// Display the list of users (accessible to all users)
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10' cellspacing='0' style='margin: auto; margin-bottom:200px; border-collapse: collapse; width: 90%; background-color: #222; color: #fff;'>";
    echo "<tr style='background-color:rgb(74, 226, 165); color: white; font-weight: bold;'>
            <th>ID</th><th>First Name</th><th>Last Name</th><th>Course</th><th>Username</th><th>Password</th><th>Admin Status</th><th>Action</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        // Display the user data normally
        echo "<tr style='background-color: #333;' id='row_" . $row['p_id'] . "'>";
        echo "<td>" . htmlspecialchars($row['p_id']) . "</td>";  // ID
        echo "<td><span id='fname_" . $row['p_id'] . "'>" . htmlspecialchars($row['p_fname']) . "</span></td>"; // First Name
        echo "<td><span id='lname_" . $row['p_id'] . "'>" . htmlspecialchars($row['p_lname']) . "</span></td>"; // Last Name
        echo "<td><span id='course_" . $row['p_id'] . "'>" . htmlspecialchars($row['course']) . "</span></td>"; // Course

        // Check if the user is logged in and their role
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'true') {
            // Admin can see usernames and passwords
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                echo "<td><span id='username_" . $row['p_id'] . "'>" . htmlspecialchars($row['username']) . "</span></td>";  // Username
                echo "<td><span id='password_" . $row['p_id'] . "'>**********</span></td>";  // Password
            } else {
                // Regular user can only see masked usernames and passwords
                echo "<td>**********</td>";  // Masked Username
                echo "<td>**********</td>";  // Masked Password
            }
        } else {
            // Non-logged-in users can only see masked usernames and passwords
            echo "<td>**********</td>";  // Masked Username
            echo "<td>**********</td>";  // Masked Password
        }

        // Show Admin status
        if ($row['is_admin'] == 1) {
            echo "<td style='color: lightgreen;'>Admin</td>";
        } else {
            echo "<td style='color: lightcoral;'>User</td>";
        }

        // Show Delete button
        echo "<td>
                <button onclick='deleteUser(" . $row['p_id'] . ")' id='delete_button_" . $row['p_id'] . "'>Delete</button>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div style='text-align: center; color: red;'>No users found.</div>";
}

$db->close();
?>

<script>
    function deleteUser(userId) {
        // Ask for confirmation before deleting
        var confirmation = confirm("Are you sure you want to delete this user?");
        if (confirmation) {
            // Make an AJAX request to delete the user from the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "modify/delete_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status == 200) {
                    alert("User deleted successfully!");
                    document.getElementById('row_' + userId).remove(); // Remove the user row from the table
                } else {
                    alert("Error deleting user: " + xhr.statusText); // Show detailed error
                }
            };

            var postData = "user_id=" + userId;
            xhr.send(postData);
        }
    }
</script>
