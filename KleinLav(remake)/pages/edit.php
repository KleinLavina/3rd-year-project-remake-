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
    echo "<table border='1' cellpadding='10' cellspacing='0' style='margin: auto; margin-bottom:200px; border-collapse: collapse; width: 90%; background-image: url(\"your-image-path.jpg\"); background-size: cover; color: #fff;'>
            <tr style='background-color: rgb(74, 226, 165); color: white; font-weight: bold;'>
                <th>ID</th><th>First Name</th><th>Last Name</th><th>Course</th><th>Username</th><th>Password</th><th>Admin Status</th><th>Action</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        // Display the user data normally
        echo "<tr style='background-color: #333;' id='row_" . $row['p_id'] . "'>";
        echo "<td>" . htmlspecialchars($row['p_id']) . "</td>";  // ID
        echo "<td><span id='fname_" . $row['p_id'] . "'>" . htmlspecialchars($row['p_fname']) . "</span><input type='text' id='edit_fname_" . $row['p_id'] . "' value='" . htmlspecialchars($row['p_fname']) . "' style='display:none;'></td>"; // First Name
        echo "<td><span id='lname_" . $row['p_id'] . "'>" . htmlspecialchars($row['p_lname']) . "</span><input type='text' id='edit_lname_" . $row['p_id'] . "' value='" . htmlspecialchars($row['p_lname']) . "' style='display:none;'></td>"; // Last Name
        echo "<td><span id='course_" . $row['p_id'] . "'>" . htmlspecialchars($row['course']) . "</span><input type='text' id='edit_course_" . $row['p_id'] . "' value='" . htmlspecialchars($row['course']) . "' style='display:none;'></td>"; // Course

        // Check if the user is logged in and their role
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'true') {
            // Admin can see usernames and passwords
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                echo "<td><span id='username_" . $row['p_id'] . "'>" . htmlspecialchars($row['username']) . "</span><input type='text' id='edit_username_" . $row['p_id'] . "' value='" . htmlspecialchars($row['username']) . "' style='display:none;'></td>";  // Username
                // Show the actual password (hash) for admin but allow it to be updated
                echo "<td><span id='password_" . $row['p_id'] . "'>" . htmlspecialchars($row['passwords']) . "</span><input type='password' id='edit_password_" . $row['p_id'] . "' value='" . htmlspecialchars($row['passwords']) . "' style='display:none;'></td>";  // Password
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

        // Show Edit/Update button
        echo "<td>
                <button onclick='editUser(" . $row['p_id'] . ")' id='edit_button_" . $row['p_id'] . "'>Edit</button>
                <button onclick='updateUser(" . $row['p_id'] . ")' id='update_button_" . $row['p_id'] . "' style='display:none;'>Update</button>
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
    function updateUser(userId) {
        // Get the updated values
        var fname = document.getElementById('edit_fname_' + userId).value;
        var lname = document.getElementById('edit_lname_' + userId).value;
        var course = document.getElementById('edit_course_' + userId).value;
        var username = document.getElementById('edit_username_' + userId).value;
        var password = document.getElementById('edit_password_' + userId).value;

        // Make an AJAX request to update the user data in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "modify/update_user.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status == 200) {
                alert("User updated successfully!");
                location.reload(); // Reload the page to see the updated data
            } else {
                alert("Error updating user: " + xhr.statusText); // Show detailed error
            }
        };

        var postData = "user_id=" + userId +
                       "&fname=" + encodeURIComponent(fname) +
                       "&lname=" + encodeURIComponent(lname) +
                       "&course=" + encodeURIComponent(course) +
                       "&username=" + encodeURIComponent(username) +
                       "&password=" + encodeURIComponent(password);

        xhr.send(postData);
    }

    function editUser(userId) {
        // Show the editable fields
        document.getElementById('edit_fname_' + userId).style.display = 'inline';
        document.getElementById('edit_lname_' + userId).style.display = 'inline';
        document.getElementById('edit_course_' + userId).style.display = 'inline';
        document.getElementById('edit_username_' + userId).style.display = 'inline';
        document.getElementById('edit_password_' + userId).style.display = 'inline';

        // Hide the non-editable fields
        document.getElementById('fname_' + userId).style.display = 'none';
        document.getElementById('lname_' + userId).style.display = 'none';
        document.getElementById('course_' + userId).style.display = 'none';
        document.getElementById('username_' + userId).style.display = 'none';
        document.getElementById('password_' + userId).style.display = 'none';

        // Show the Update button and hide the Edit button
        document.getElementById('edit_button_' + userId).style.display = 'none';
        document.getElementById('update_button_' + userId).style.display = 'inline';
    }
</script>
